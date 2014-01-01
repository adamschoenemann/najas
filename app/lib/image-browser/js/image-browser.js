$(function(){

  var ImageModel = Backbone.Model.extend({

    // urlRoot: "/najas/api/images",
    // idAttribute: "_id",

    defaults: function(){
      return {
        title: "Untitled Image",
        // path: "http://images3.wikia.nocookie.net/__cb20130826211346/creepypasta/images/0/01/DOGE.png",
        path: "",
        description: "",
        selected: false
      };
    },

    toggleSelected: function(){
      this.set("selected", !(this.get("selected")));
    }

  });

  var ImageView = Backbone.View.extend({

    tagName: "li",
    className: "imgbrowser-li",
    template: _.template($("#image-template").html()),

    events: {
      // "click": "onClick",
      "mouseenter": "onMouseEnter",
      "mouseleave": "onMouseLeave",
      "click #delete_ic": "onDeleteClick",
      "click #edit_ic": "onEditClick",
      "click #check_ic": "onCheckClick"
    },

    initialize: function(){
      this.listenTo(this.model, "change", this.render);
      this.listenTo(this.model, 'destroy', this.remove);
    },

    onCheckClick: function(){
      this.model.toggleSelected();
    },

    onEditClick: function(){
      var list = new ImageList();
      var editView = new EditImagesView({collection: list});
      list.add(this.model);
      $("body").append(editView.render().$el);
    },

    onDeleteClick: function(){
      this.remove();
      this.trigger("destroy");
    },

    onMouseLeave: function(){
      var overlay = this.$el.find(".overlay");
      overlay.fadeOut(100, function(){
        overlay.css("visibility", "invisible");
      });
    },

    onMouseEnter: function(){
      var overlay = this.$el.find(".overlay");
      overlay.css("visibility", "visible");
      overlay.fadeTo(100, 1);
    },

    onClick: function(){
      alert("Image was clicked!");
    },

    render: function(){
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    }


  });

  var ImageList = Backbone.Collection.extend({

    url: "/najas/api/images/",
    model: ImageModel,

    events: {
      "destroy": "onDestroy"
    },

    selected: function(){
      return this.where({selected: true});
    },

    onDestroy: function(){
      console.log("destroyed!");
    }

  });

  window.imgList = new ImageList();

  // ======================= ImageBrowserView ==========================//

  var ImageBrowserView = Backbone.View.extend({

    el: "#img-browser-container",
    template: _.template($("#image-browser-template").html()),
    
    initialize: function(){
      this.listenTo(this.collection, "add", this.addOne);
      this.listenTo(this.collection, "change", this.onSelected);
      this.collection.fetch();
      this.render();
    },

    events: {
      "click #delete_ic": "onDeleteClick",
      "click #add_ic": "onAddClick"
    },

    addOne: function(model){
      // console.log("addOne");
      var view = new ImageView({"model": model});
      this.$el.find("ul").append(view.render().el);
    },

    onSelected: function(){
      var delIcon = this.$el.find("#delete_ic");
      var selected = this.hasSelected();
      delIcon.attr("src",
        selected ? 
          '../app/lib/image-browser/icon/Bin-32.png' :
          '../app/lib/image-browser/icon/Bin-Grey-32.png'
      );
    },

    // TODO: This is not properly propagating to the collection,
    // where it should be removed. Investigate!
    onDeleteClick: function(){
      var imgs = this.collection.selected();
      _.each(imgs, function(ele){
        // this.collection.remove(this.model);
        // ele.trigger("destroy");
        ele.collection.remove(ele);
      }, this);
    },

    onAddClick: function(){
      var addView = new AddImageView();
      $("body").append(addView.render().$el);
    },

    render: function(){
      this.$el.html(this.template());
      // this.$el.append(this.imgView.render().el);
    },

    hasSelected: function(){
      return (this.collection.selected().length > 0);
    }

  });
  
  // ======================= EditImagesView ==========================//
  var EditImagesView = Backbone.View.extend({

    tagName: "div",    
    className: "edit-image",
    template: _.template($("#modal-box-template")),

    events: {
      "click #close_ic": "onCloseBtnClick",
      "click .cancel-btn": "onCloseBtnClick",
      "click .submit-btn": "onSubmitBtnClick",
      "keypress input": "onInputKeypress"
    },

    initialize: function(){

    },

    render: function(){
      this.$el.html(this.template({body: ""}));
      return this;
    },

    onCloseBtnClick: function(){
      this.remove();
    },

    onSubmitBtnClick: function(){
      var inputs = this.$el.find("input");
      var self = this;
      inputs.each(function(index, item){
        self.saveAttr(item.id, item.value);
      });
      this.remove();
    },

    onInputKeypress: function(e){
      // console.log(e.target.id);
      // if(e.keyCode == 13) this.saveAttr(e.target.id, e.target.value);
    },

    saveAttr: function(attr, val){
      console.log("saveAttr: " + attr + ": " + val);
      var obj = {};
      obj[attr] = val;
      this.model.save(obj);
    }

  });
  
  // ======================= ImageListView ==========================//
  var ImageListView = Backbone.View.extend({

    tagName: "div",
    className: "image-list-view",
    template: _.template($("#image-list-view-template").html()),

    initialize: function(){
      this.listenTo(this.collection, "add", this.addOne);
      // this.listenTo(this.collection, "change", this.onSelected);
    },

    addOne: function(model){
      console.log("addOne: " + model);
      var view = new ImageRowView({"model": model});
      this.$el.find("table").append(view.render().$el);
    },

    render: function(){
      this.$el.html(this.template());
      return this;
    }


  });

  // ======================= ImageRowView ==========================//
  var ImageRowView = Backbone.View.extend({

    tagName: "tr",
    className: "image-row-view",
    template: _.template($("#image-row-view-template").html()),

    initialize: function(){
      this.listenTo(this.model, "change", this.render);
      this.listenTo(this.model, 'destroy', this.remove);
    },

    render: function(){
      this.$el.html(this.template(this.model.toJSON()));
      this.onRendered();
      return this;
    },

    onRendered: function(){
      var self = this;
      // Fix the file replace rest API call
      this.$el.find("#replace-img").fileupload({
        url: "/najas/api/images",
        // fileInput: self.$el.find("#dummy-file-upload"),
        dataType: "json",
        type: "PUT",
        formData: [
          {
            name: 'id',
            value: self.model.get("id")
          }
        ],
        done: function(e, data){
          self.model.set("path", data.result.path);
        }
      });
    }


  })

  // ======================= AddImageview ==========================//
  var AddImageView = Backbone.View.extend({

    tagName: "div",    
    className: "add-image",
    imgListView: null,

    initialize: function(){
      
    },

    template: function(options){
      var first = _.template($("#modal-box-template").html());
      var second = _.template($("#add-image-template").html());
      var results = second(options);
      var obj = {};
      obj["body"] = results;
      return first(obj);
    },

    events: {
      "click #close_ic": "onCloseBtnClick",
      "click .cancel-btn": "onCloseBtnClick",
      "click .submit-btn": "onSubmitBtnClick",
      "keypress input": "onInputKeypress"
    },

    render: function(){
      this.$el.html(this.template({}));
      this.onRendered();
      return this;
    },

    onRendered: function(){
      var self = this;
      this.$el.find("#fileupload").fileupload({
        dataType: "json",
        url: "/najas/api/images",
        done: function(e, data){
          var model = new ImageModel({
            id: data.result.id
          });
          console.log(data.result);
          if(self.imgListView == null){
            self.imgListView = new ImageListView({collection: new ImageList()});
            self.$el.find(".modal-box-body").append(self.imgListView.render().$el);
          }

          self.imgListView.collection.add(model);
          var xhr = model.fetch();
          // xhr.done(function(){
          //   self.$el.find("#image-list").append(
          //     "<tr><td><img src='../uploads/" + model.get("path") + "' /></td></tr>"
          //   );
          // });
        }
      });
    },

    onCloseBtnClick: function(){
      this.remove();
    },

    onSubmitBtnClick: function(){
      var inputs = this.$el.find("input");
      var self = this;
      inputs.each(function(index, item){
        self.saveAttr(item.id, item.value);
      });
      this.remove();
    }

  });

  window.imgBrowserView = new ImageBrowserView({collection: window.imgList});
  
  // var imgView = new ImageView({model:{src: "hey"}});

});