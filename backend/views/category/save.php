<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-upload-picker.js']);

$this->title = 'Menu';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">
      Menu <small class="text-muted">create & update</small>
      <a :href="href.index" class="btn btn-light float-right" v-if="init.m.id!=''">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <form>
      <div class="form-group col-4">
        <label>Parent category</label>
        <select class="form-control" v-model.number="init.m.parent_id">
          <option value="0">顶级类别</option>
          <option v-for="item in init.categoryList" :value="item.id">{{item.category}}</option>
        </select>
      </div>

      <div class="form-group col-8">
        <label>Category <small>*</small></label>
        <input type="text" class="form-control" v-model.trim="init.m.category" placeholder="Enter category name">
      </div>

      <div class="form-group col-8">
        <label>Alias <small>*</small></label>
        <input type="text" class="form-control" v-model.trim="init.m.alias" placeholder="Enter category alias">
      </div>

      <div class="form-group col-8">
        <label>Icon</label>
        <vue-upload-picker
          v-model="init.m.pic"
          post-uri="/upload/ajax-upload"
          title="Upload category icon"
          icon='<i class="fas fa-file-import"></i>'
          class-name="btn btn-info btn-sm"></vue-upload-picker>
      </div>

      <div class="form-group col-8">
        <label>Remark <small>*</small></label>
        <textarea class="form-control" rows="3" v-model.trim="init.m.remark" placeholder="Enter remark"></textarea>
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
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        m: <?=Json::encode($m, true)?>,
        categoryList: <?=Json::encode($category_list, true)?>,
      },
      href: {
        index: "<?=URL::to(['/category/list'], true)?>",
        save: "<?=Url::to(['/category/ajax-save'], true)?>",
      },
    };
  },
  computed: {
    isValid() {
      var m = this.init.m;
      if (m.category == ''
        || m.remark == ''
        || m.alias == ''
      ) {
        return false;
      }

      return true;
    }
  },
  methods: {
    save(event) {
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      var _this = this;
      $.post(this.href.save, {
        data: this.init.m
      }, function (response) {
        if (response.status) {
          window.location.href = _this.href.index;
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
