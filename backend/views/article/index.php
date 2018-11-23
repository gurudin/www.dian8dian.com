<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-toggle-button.min.js']);

$this->title = 'Article List';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Article
      <a :href="init.href.save" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> Create
      </a>
    </h4>
    <hr>

    <form>
      <div class="form-row">
        <div class="col-md-3 mb-3">
          <label>Category</label>
          <select class="form-control" v-model="keys.fk_category_id">
            <option value="">-- Select category --</option>
            <option v-for="item in init.category" :value="item.id">{{item.category}}</option>
          </select>
        </div>

        <div class="col-md-2 mb-3">
          <label>Status</label>
          <select class="form-control" v-model="keys.status">
            <option value="">-- All status --</option>
            <option value="0">Offline</option>
            <option value="1">Online</option>
            <option value="2">To audit</option>
          </select>
        </div>

        <div class="col-md-1 ml-auto">
          <label></label><br>
          <button type="button" class="btn float-right btn-primary" @click="search">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Category</th>
          <th scope="col">Tags</th>
          <th scope="col">Author</th>
          <th>Status</th>
          <th>Created at</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item,inx) in init.list">
          <th scope="row">{{item.id}}</th>
          <td>{{item.title}}</td>
          <td>{{getName(item.fk_category_id)}}</td>
          <td>{{item.tags}}</td>
          <td>{{item.author}}</td>
          <td>
            <toggle-button
              :data="item"
              v-model="item.status"
              size="sm"
              :options="options"
              :before="editStatus"></toggle-button>
          </td>
          <td>{{date.format('Y/M/D H:m', item.created_at)}}</td>
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
      <div class="float-left"><?=$result['page']->totalCount?> entries</div>
      <div class="float-right">
        <?= common\widgets\LinkPager::widget(['pagination' => $result['page']]); ?>
      </div>
    </div>
  </div>
</div>

<?php $this->beginBlock('js'); ?>
<script>

Vue.prototype.date = date;
Vue.component('toggle-button', VueToggleButton.toggleButton);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        list: <?=Json::encode($result['list'], true)?>,
        category: <?=Json::encode($category, true)?>,
        href: {
          current: "<?=Url::to(['/article/index'], true)?>",
          save: "<?=Url::to(['/article/save'], true)?>",
          edit: "<?=Url::to(['/article/ajax-edit-status'], true)?>",
          remove: "<?=Url::to(['/article/ajax-remove'], true)?>",
        },
      },
      options: [
        {label: "Online", value: 1, "checked": "success"},
        {label: "Offline", value: 0, checked: "warning"}
      ],
      keys: <?=Json::encode($search, true)?>,
    };
  },
  methods: {
    getName(category_id) {
      var categoryName = '';
      this.init.category.forEach(row =>{
        if (row.id == category_id) {
          categoryName = row.category;
        }
      });
      
      return categoryName;
    },
    toEdit(id) {
      window.location = getUrl(this.init.href.save, {id: id});
    },
    search() {
      window.location.href = getUrl(this.init.href.current, this.keys);
    },
    editStatus(obj) {
      var $btn = $(obj.event.currentTarget).loading('loading');

      $.post(this.init.href.edit, {
        id: obj.data.id,
        status: obj.value
      }, function (response) {
        if (response.status) {
          obj.data.status = obj.value;
        } else {
          $.alert(response.msg);
        }
        $btn.loading('reset');
      });

      return false;
    },
    remove(event, id, inx) {
      if (!confirm('Are you sure to delete this item?')) {
        return false;
      }

      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      var _this = this;
      $.post(this.init.href.remove, {id: id}, function (response) {
        if (response.status) {
          _this.init.list.splice(inx, 1);
        } else {
          $.alert(response.msg);
        }
        $btn.loading('reset');
      });
    }
  }
});
</script>
<?php $this->endBlock(); ?>
