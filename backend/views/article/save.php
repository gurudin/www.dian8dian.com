<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-tags-input.min.js']);
\backend\assets\AppAsset::addCss($this, ['vue-tags-input.css']);
\backend\assets\AppAsset::addScript($this, ['tinymce/tinymce.min.js']);
\backend\assets\AppAsset::addScript($this, ['vue-upload-picker.js']);
\backend\assets\AppAsset::addScript($this, ['vue-select.js']);

$this->title = 'Article create & update';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Article <small class="text-muted">create & update</small>
      <a href="<?=URL::to(['/article/index'])?>" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <form>
      <div class="form-group col-8">
        <label>Article title <small>*</small></label>
        <input type="text" class="form-control" v-model.trim="init.m.title" placeholder="Enter article title">
      </div>

      <div class="form-group col-8">
        <label>Category <small>*</small></label>
        <v-select label="category" v-model="bindCategoryData" :options="init.category"></v-select>
      </div>

      <div class="form-group col-8">
        <label>Author <small>*</small></label>
        <input type="text" class="form-control" v-model.trim="init.m.author" placeholder="Enter author">
      </div>

      <div class="form-group col-8">
        <label>Weight</label>
        <input type="number" class="form-control" v-model.number="init.m.weight" placeholder="Enter weight">
      </div>

      <div class="form-group col-8">
        <label>Cover</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Images content" ref="auto-image-name">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" @click="generateImage">
              <i class="fas fa-images"></i> Automatically generate images
            </button>
          </div>
        </div>

        <vue-upload-picker
          v-model="init.m.cover"
          post-uri="/upload/ajax-upload"
          title="Upload article cover"
          icon='<i class="fas fa-file-import"></i>'
          class-name="btn btn-info btn-sm"></vue-upload-picker>
      </div>

      <div class="form-group col-8">
        <label>Source</label>
        <input type="text" class="form-control" v-model.trim="init.m.source" placeholder="Enter source url">
      </div>

      <div class="form-group col-8">
        <label>Demo</label>
        <input type="text" class="form-control" v-model.trim="init.m.demo" placeholder="Enter demo url">
      </div>

      <div class="form-group col-8">
        <label>Enter tags</label>
        <tags-input
          label-style="secondary"
          :data-value="tagsData"
          :on-change="tagChange"
          ref="tags"></tags-input>
      </div>

      <div class="form-group col-8">
        <label>Remark *</label>
        <textarea class="form-control" rows="3" v-model.trim="init.m.remark" placeholder="Enter remark"></textarea>
      </div>

      <div class="form-group col-10">
        <label>Content *</label>
        <textarea id="content"></textarea>
      </div>

      <div class="form-group col-4">
        <button type="button" class="btn btn-success" :disabled="!isValid" @click="save">
          <i class="fas fa-paper-plane"></i> Save
        </button>
      </div>
    </form>
  </div>
</div>

<?php $this->beginBlock('js'); ?>
<script>
tinymce.init({
  selector: '#content',
  // language: 'zh_CN',
  browser_spellcheck: true,
  contextmenu: false,
  height: 300,
  plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks  fullscreen codesample",
    "insertdatetime media table contextmenu paste imagetools wordcount",
  ],
  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | codesample | preview | fullscreen",
  convert_urls: false,
  images_upload_handler: function (blobInfo, success, failure) {
    var data = new FormData();
    data.append('file', blobInfo.blob());
    $.ajax({
      url: "<?=Url::to(['/upload/ajax-upload'])?>",
      type: 'POST',
      cache: false,
      data: data,
      processData: false,
      contentType: false
    }).done(function (response) {
      if (response.status) {
        success(response.path);
      } else {
        failure(response.msg);
      }
    }).fail(function (res) {
      console.log(res);
      failure('Upload error.')
    });
  },
  init_instance_callback: function (editor) {
    tinymce.activeEditor.setContent(vm.init.m.content);
    
    editor.on('KeyUp', function (e) {
      vm.init.m.content = tinymce.activeEditor.getContent();
    });

    editor.on('Change', function (e) {
      vm.init.m.content = tinymce.activeEditor.getContent();
    });
  }
});

Vue.component('tags-input', VueTagsInput.tagsInput);
Vue.component('v-select', VueSelect.VueSelect);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        m: <?=Json::encode($m, true)?>,
        category: <?=Json::encode($category, true)?>,
        href: {
          generate: "<?=Url::to(['upload/ajax-generate'], true)?>",
        },
      },
      bindCategory: '',
    };
  },
  computed: {
    isValid() {
      var m = this.init.m;
      
      if (m.fk_category_id == ''
        || m.title == ''
        || m.remark == ''
        || m.author == ''
        || m.content == ''
      ) {
        return false;
      }

      return true;
    },
    tagsData () {
      return this.init.m.tags.split(",");
    },
    bindCategoryData: {
      get() {
        return this.bindCategory;
      },
      set(value) {
        this.init.m.fk_category_id = value ? value.id : '';
      }
    },
  },
  methods: {
    tagChange(value) {
      this.init.m.tags = value.join(",");
    },
    generateImage(event) {
      var imgName = this.$refs['auto-image-name'].value;
      if (imgName == '') {
        return false;
      }
      
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i> loading...');
      var _this = this;
      $.post(this.init.href.generate, {title: imgName}, function (response) {
        if (response.status) {
          _this.init.m.cover = response.path;
        } else {
          $.alert({message: response.msg});
        }
        $btn.loading('reset');
      });
    },
    save() {
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      $.post("<?=Url::to(['/article/ajax-save'])?>", {
        data: this.init.m
      }, function (response) {
        if (response.status) {
          window.location.href = "<?=URL::to(['/article/index'])?>";
        } else {
          $.alert({message: response.msg});
          $btn.loading('reset');
        }
      });
    },
  }
});
</script>
<?php $this->endBlock(); ?>