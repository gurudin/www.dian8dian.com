<?php
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Spider List';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Spider 
      <a :href="init.href.save" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> Create
      </a>
    </h4>
    <hr>

    <form class="form-inline">
      <!-- <div class="input-group mb-2">
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
      </div> -->
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Title</th>
          <th scope="col">Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,inx) in init.list">
          <td>{{item.parent_name == '' ? '' : item.parent_name + ' /'}} {{item.title}}</td>
          <td v-html="getStatus(item.status)"></td>
          <td>
            <button
              type="button"
              class="btn btn-warning btn-sm text-white"
              @click="toEdit(item.id)"
              data-toggle="tooltip" data-placement="top" title="Tooltip on top">
              <i class="fas fa-edit"></i></button>

            <button
              type="button"
              class="btn btn-success btn-sm text-white"
              @click="toSave(item.id)">
              <i class="fas fa-file-signature"></i></button>

            <button
              type="button"
              class="btn btn-info btn-sm text-white"
              @click="toCopy(item.id)"
              title="Copy">
              <i class="fas fa-copy"></i></i></button>

            <button
              type="button"
              class="btn btn-primary btn-sm text-white"
              @click="openModel(item)">
              <i class="fas fa-eye"></i></button>
            
            <button
              type="button"
              class="btn btn-danger btn-sm text-white"
              @click="remove(item.id, inx)">
              <i class="fas fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
    
    <div>
      <div class="float-right">
        <?= common\widgets\LinkPager::widget(['pagination' => $page]); ?>
      </div>
    </div>
  </div>

  <!-- Spider data modal -->
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{currentItem.title}} <small>Spider data</small></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <pre class="bg-dark text-white p-2"><code>{{toJson(currentItem.data)}}</code></pre>
        </div>
      </div>
    </div>
  </div>
  <!-- /Spider data modal -->

</div>

<?php $this->beginBlock('js'); ?>
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        list: <?=Json::encode($list, true)?>,
        href: {
          save: "<?=Url::to(['/spider/spider'], true)?>",
          remove: "<?=Url::to(['spider/ajax-remove'], true)?>",
          create: "<?=Url::to(['article/save'], true)?>",
        }
      },
      currentItem: '',
    };
  },
  methods: {
    toJson(str) {
      return eval('(' + str + ')');
    },
    getParentName(parent_id) {
      if (parent_id == 0) return '';

      let parent = '';
      this.init.list.forEach(row =>{
        if (row.parent_id == parent_id) {
          parent = row.title;
        }
      });

      return parent;
    },
    getStatus(status) {
      let statusText = {'1': '未爬取', '2': '添加到文章', '3': '添加到规则', '4': '规则错误'};
      if (status == 1) {
        return '<span class="text-secondary">' + statusText[status] + '</span>';
      }
      if (status == 2) {
        return '<span class="text-success">' + statusText[status] + '</span>';
      }
      if (status == 3) {
        return '<span class="text-info">' + statusText[status] + '</span>';
      }
      if (status == 4) {
        return '<span class="text-danger">' + statusText[status] + '</span>';
      }
    },
    toEdit(id) {
      window.location = getUrl(this.init.href.save, {id: id});
    },
    toChild(parent_id) {
      window.location = getUrl(this.init.href.save, {parent_id: parent_id});
    },
    toCopy(id) {
      window.location = getUrl(this.init.href.save, {copy_id: id});
    },
    toSave(id) {
      window.location = getUrl(this.init.href.create, {rule_id: id});
    },
    openModel(item) {
      $(".bd-example-modal-lg").modal('show');
      this.currentItem = item;
    },
    remove(id, inx) {
      if (!confirm('Are you sure to delete this item?')) {
        return false;
      }

      var $btn = $(event.currentTarget).loading('<i class="fas fa-spinner fa-spin"></i>');
      var _this = this;
      $.post(this.init.href.remove, {id: id}, function (response) {
        if (response.status) {
          _this.init.list.splice(inx, 1);
        } else {
          $.alert({message: response.msg});
        }
        $btn.loading('reset');
      });
    }
  },
});
</script>
<?php $this->endBlock(); ?>
