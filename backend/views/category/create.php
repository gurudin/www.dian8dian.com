<?php
use yii\helpers\Json;
use common\widgets\Alert;

$this->title = 'Create Menu';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Create Menu <small class="text-muted">category/tags</small></h4>
    <hr>

    <form>
      <div class="form-group col-4">
        <label>Parent category</label>
        <select class="form-control">
          <option value="0">顶级类别</option>
        </select>
      </div>

      <div class="form-group col-8">
        <label>Category</label>
        <input type="text" class="form-control" placeholder="Enter category name">
      </div>

      <div class="form-group col-8">
        <label>Remark</label>
        <textarea class="form-control" rows="3" placeholder="Enter remark"></textarea>
      </div>

      <div class="form-group col-4">
        <button type="button" class="btn btn-success" @click="save"><i class="fas fa-paper-plane"></i> Save</button>
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
        'm': <?=Json::encode($m, true)?>,
      },
    };
  },
  methods: {
    save(event) {
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      setTimeout(() => {
        
        $btn.loading('reset');
      }, 1000);
    }
  },
  created() {
    // console.log(this.init.m);
  }
});
</script>
<?php $this->endBlock(); ?>
