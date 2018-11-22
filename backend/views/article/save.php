<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-tags-input.min.js']);
\backend\assets\AppAsset::addCss($this, ['vue-tags-input.css']);
\backend\assets\AppAsset::addScript($this, ['tinymce/tinymce.min.js']);

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
        <input type="text" class="form-control" placeholder="Enter article title">
      </div>

      <div class="form-group col-8">
        <label>Category <small>*</small></label>
        <select class="form-control">
          <option value="">--Select category--</option>
          <option v-for="item in init.category" :value="item.id">{{item.category}}</option>
        </select>
      </div>

      <div class="form-group col-8">
        <label>Author <small>*</small></label>
        <input type="text" class="form-control" placeholder="Enter author">
      </div>

      <div class="form-group col-8">
        <label>Source</label>
        <input type="text" class="form-control" placeholder="Enter source url">
      </div>

      <div class="form-group col-8">
        <label>Demo</label>
        <input type="text" class="form-control" placeholder="Enter demo url">
      </div>

      <div class="form-group col-8">
        <label>Enter tags</label>
        <tags-input
          label-style="secondary"></tags-input>
      </div>

      <div class="form-group col-8">
        <label>Remark *</label>
        <textarea class="form-control" rows="3" placeholder="Enter remark"></textarea>
      </div>

      <div class="form-group col-10">
        <label>Content *</label>
        <textarea id="content"></textarea>
      </div>

      <div class="form-group col-4">
        <button type="button" class="btn btn-success">
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
  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code | preview | fullscreen",
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
  }
});
Vue.component('tags-input', VueTagsInput.tagsInput);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        m: <?=Json::encode($m, true)?>,
        category: <?=Json::encode($category, true)?>,
      },
    };
  },
});
</script>
<?php $this->endBlock(); ?>