<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-toggle-button.min.js']);

$this->title = 'Menu List';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Menu 
      <a :href="init.href.save" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> Create
      </a>
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
          <th scope="col">Weight</th>
          <th scope="col">Category</th>
          <th scope="col">Alias</th>
          <th scope="col">Icon</th>
          <th scope="col">Parent Name</th>
          <th>Enabled</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,inx) in listData">
          <th scope="row">{{item.weight}}</th>
          <td>{{item.category}}</td>
          <td>{{item.alias}}</td>
          <td>
            <a :href="item.pic" target="_blank" v-if="item.pic != ''">
              <img :src="item.pic" class="rounded" width="35">
            </a>
          </td>
          <td>{{retParentName(item.parent_id)}}</td>
          <td>
            <toggle-button
              :data="item"
              v-model="item.enabled"
              size="sm"
              :options="options"
              :before="editEnabled"></toggle-button>
          </td>
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
Vue.component('toggle-button', VueToggleButton.toggleButton);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        list: <?=Json::encode($list, true)?>,
        categoryList: <?=Json::encode($category_list, true)?>,
        href: {
          save: "<?=URL::to(['/category/save'], true)?>",
          enabled: "<?=URL::to(['/category/ajax-enabled'], true)?>"
        }
      },
      options: [
        {label: "Active", value: 1, "checked": "success"},
        {label: "Deactive", value: 0, checked: "warning"}
      ],
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
      window.location = getUrl(this.init.href.save, {id: id});
    },
    editEnabled(obj) {
      $.post(this.init.href.enabled, {
        id: obj.data.id,
        enabled: obj.value
      }, function (response) {
        if (response.status) {
          obj.data.enabled = obj.value;
        } else {
          $.alert(response.msg);
        }
      });
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
