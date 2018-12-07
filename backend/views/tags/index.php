<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-toggle-button.min.js']);

$this->title = 'Tags List';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Tags
      <button class="btn btn-outline-success float-right" @click="openModal('')">
        <i class="fas fa-file-signature"></i> Create
      </button>
    </h4>
    <hr>

    <form>
      <div class="form-row">
        <div class="col-md-3 mb-3">
          <label>Title</label>
          <input type="text" class="form-control" v-model="keys.search" placeholder="Enter title or alias">
        </div>

        <div class="col-md-3 mb-3">
          <label>Category</label>
          <select class="form-control" v-model="keys.categoryId">
            <option value="">-- Select category --</option>
            <option v-for="item in init.category" :value="item.id">{{item.category}}</option>
          </select>
        </div>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Category</th>
          <th scope="col">Alias</th>
          <th scope="col">Recommend</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item,inx) in listData">
          <th scope="col">{{inx+1}}</th>
          <td>{{item.title}}</td>
          <td>{{item.category}}</td>
          <td>{{item.alias}}</td>
          <td>
            <toggle-button
              :data="item"
              v-model="item.recommend"
              size="sm"
              :options="options"
              :before="editEnabled"></toggle-button>
          </td>
          <td>
            <button
              type="button"
              class="btn btn-warning btn-sm text-white"
              @click="openModal(item)">
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
      <div class="float-left">{{listData.length}} entries</div>
      <div class="float-right">
        <?= common\widgets\LinkPager::widget(['pagination' => $page]); ?>
      </div>
    </div>
  </div>
</div>

<!-- Save tags modal -->
<div class="modal fade" id="saveModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Save tags</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Category</label>
          <select class="form-control" v-model="init.m.fk_category_id">
            <option value="">-- Select One --</option>
            <option v-for="item in init.category" :value="item.id">{{item.category}}</option>
          </select>
        </div>
        <div class="form-group">
          <label>Tags</label>
          <input type="text" class="form-control" v-model="init.m.title" placeholder="Enter tags">
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-success"
          :disabled="init.m.fk_category_id=='' || init.m.title==''"
          @click="save">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- /Save tags modal -->

<?php $this->beginBlock('js'); ?>
<script>
Vue.component('toggle-button', VueToggleButton.toggleButton);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        list: <?=Json::encode($list, true)?>,
        category: <?=Json::encode($category, true)?>,
        m: <?=Json::encode($m, true)?>,
        href: {
          index: "<?=Url::to(['/tags/index'], true)?>",
          save: "<?=Url::to(['/tags/ajax-save'], true)?>",
          remove: "<?=Url::to(['/tags/ajax-remove'], true)?>",
          recommend: "<?=Url::to(['/tags/ajax-recommend'], true)?>",
        },
      },
      options: [
        {label: "Top", value: 1, checked: "success"},
        {label: "Undo", value: 0, checked: "warning"}
      ],
      keys: {
        categoryId: '',
        search: '',
      },
    };
  },
  computed: {
    listData() {
      var data = this.init.list;
      var keys = this.keys;
      if (keys.categoryId != '') {
        data = data.filter(row =>{
          return row.fk_category_id == keys.categoryId;
        });
      }
      if (keys.search != '') {
        data = data.filter(row =>{
          return (row.title + ' ' + row.alias).indexOf(keys.search) > -1;
        });
      }

      return data;
    }
  },
  methods: {
    openModal(item) {
      if (item == '') {
        this.init.m = <?=Json::encode($m, true)?>;
      } else {
        this.init.m = item;
      }

      $("#saveModal").modal('show');
    },
    editEnabled(obj) {
      console.log(obj);
      
      $.post(this.init.href.recommend, {
        id: obj.data.id,
        recommend: obj.value
      }, function (response) {
        if (response.status) {
          obj.data.recommend = obj.value;
        } else {
          $.alert(response.msg);
        }
      });
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
    },
    save(event) {
      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      $.post(this.init.href.save, this.init.m, function (response) {
        if (response.status) {
          window.location.reload();
        } else {
          $.alert({message: response.msg});
        }
        $btn.loading('reset');
      });
    },
  }
});
</script>
<?php $this->endBlock(); ?>
