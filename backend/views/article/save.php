<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-tags-input.min.js']);
\backend\assets\AppAsset::addCss($this, ['vue-tags-input.css']);

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

      <div class="form-group col-8">
        <label>Content *</label>
        <textarea class="form-control" rows="5"></textarea>
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