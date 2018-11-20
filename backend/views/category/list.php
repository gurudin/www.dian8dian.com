<?php
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Menu List';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Menu 
    <a href="<?=URL::to(['/category/save'])?>" class="btn btn-success float-right">Create</a>
    </h4>
    <hr>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Category</th>
          <th scope="col">Parent Name</th>
          <th scope="col">Search Text</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,inx) in init.list">
          <th scope="row">{{inx+1}}</th>
          <td>{{item.category}}</td>
          <td>{{retParentName(item.parent_id)}}</td>
          <td>{{item.search_text}}</td>
          <td>
            <button
              type="button"
              class="btn btn-warning btn-sm text-white"
              @click="toEdit(item.id)">
              <i class="fas fa-edit"></i></button>

            <button
              type="button"
              class="btn btn-danger btn-sm"
              @click="remove($event, item.id, inx)">
              <i class="fas fa-trash-alt"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
    
    <div>
      <div class="float-right">
        {{init.list.length}} entries
      </div>
    </div>
  </div>
</div>

<?php $this->beginBlock('js'); ?>
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        list: <?=Json::encode($list, true)?>,
      },
    };
  },
  computed: {
    
  },
  methods: {
    retParentName(parent_id) {
      if (parent_id == 0) return '';

      var parentName = '';
      this.init.list.forEach(row =>{
        if (row.id == parent_id) {
          parentName = row.category;
        }
      });

      return parentName;
    },
    toEdit(id) {
      var url = new URL("<?=Yii::$app->request->hostInfo . Url::to(['/category/save'])?>");
      url.searchParams.append('id', id);
      
      window.location = url;
    },
    remove(event, id, inx) {
      var _this = this;
      var $btn  = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      $.post("<?=URL::to(['/category/ajax-remove'])?>", {
        id: id
      }, function (response) {
        if (response.status) {
          _this.init.list.splice(inx, 1)
        } else {
          $.alert({message: response.msg});
        }
        $btn.loading('reset');
      });
    }
  }
});
</script>
<?php $this->endBlock(); ?>
