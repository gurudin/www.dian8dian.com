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
        <tr v-for="item in init.list">
          <td>{{getParentName(item.parent_id) == '' ? '' : getParentName(item.parent_id) + '->'}} {{item.title}}</td>
          <td v-html="getStatus(item.status)"></td>
          <td>
            <button
              type="button"
              class="btn btn-warning btn-sm text-white"
              @click="toEdit(item.id)">
              <i class="fas fa-edit"></i></button>

            <button
              type="button"
              class="btn btn-info btn-sm text-white"
              @click="toChild(item.id)"
              title="添加子规则">
              <i class="fas fa-file-signature"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
    
    <div>
      <div class="float-right">
        
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
        href: {
          save: "<?=URL::to(['/spider/spider'], true)?>",
        }
      },
    };
  },
  methods: {
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
  },
});
</script>
<?php $this->endBlock(); ?>
