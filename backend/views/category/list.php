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

    <form class="form-inline">
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">Category</div>
        </div>
        <input
          type="text"
          class="form-control form-control-sm"
          v-model="search.categoryKey"
          placeholder="Enter category">
      </div>

      &nbsp;
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">Parent</div>
        </div>
        <select class="custom-select" v-model="search.parentId">
          <option value="" selected>Select parent name</option>
          <option value="-1">All Parent</option>
          <option v-for="item in init.categoryList" :value="item.id">{{item.category}}</option>
        </select>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Category</th>
          <th scope="col">Icon</th>
          <th scope="col">Parent Name</th>
          <th scope="col">Search Text</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,inx) in listData">
          <th scope="row">{{inx+1}}</th>
          <td>{{item.category}}</td>
          <td>
            <a :href="item.pic" target="_blank" v-if="item.pic != ''">
              <img :src="item.pic" class="rounded" width="35">
            </a>
          </td>
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
        {{listData.length}} entries
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
        categoryList: <?=Json::encode($category_list, true)?>,
      },
      search: {
        categoryKey: '',
        parentId: '',
      },
    };
  },
  computed: {
    listData() {
      var key = this.search.categoryKey ? this.search.categoryKey.toLowerCase() : '';
      var parentId = this.search.parentId;
      var data = this.init.list;
      
      data = data.filter(row =>{
        let tmpText = row.category + ' ' + row.search_text;
        let retParent = true;
        let retKey = true;

        if (parentId != '') {
          if (parentId == -1) {
            retParent = row.parent_id == 0;
          } else {
            retParent = row.parent_id == parentId;
          }
        }
        
        if (key != '') {
          retKey = tmpText.toLowerCase().indexOf(key) > -1;
        }
        
        return retParent && retKey;
      });

      return data;
    }
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
      if (!confirm('Are you sure to delete this item?')) {
        return false;
      }

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
