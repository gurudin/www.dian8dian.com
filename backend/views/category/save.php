<?php
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Menu';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">
      Menu <small class="text-muted">create & update</small>
      <a href="<?=URL::to(['/category/list'])?>" class="btn btn-light float-right" v-if="init.m.id!=''">
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
    };
  },
  computed: {
    isValid() {
      var m = this.init.m;
      if (m.category == ''
        || m.remark == ''
      ) {
        return false;
      }

      return true;
    }
  },
  methods: {
    save(event) {
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      $.post("<?=Url::to(['/category/ajax-save'])?>", {
        data: this.init.m
      }, function (response) {
        if (response.status) {
          window.location.href = "<?=URL::to(['/category/list'])?>";
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
