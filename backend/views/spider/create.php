<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-select.js']);

$this->title = 'Spider';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">
      Create
      <a :href="init.href.index" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <div class="form-group">
      <label class="h5">Spider message:</label>
      <pre class="bg-dark text-white p-2"><code>{{init.message}}</code></pre>
    </div>

    <form>
      <div class="form-group">
        <label>Category</label>
        <v-select label="category" v-model="bindCategoryData" :options="init.category" placeholder="搜索名称、拼音缩写或ID..."></v-select>
      </div>
      <div class="form-group">
        <label>Title</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <input type="text" class="form-control" @keyup="setRule($event.target.value, 'title')" placeholder="Rule">
          </div>
          <input type="text" class="form-control" v-model.trim="init.m.title" placeholder="Enter title">
        </div>
      </div>

      <button type="button" class="btn btn-outline-secondary">Save rule</button>
      <button type="button" class="btn btn-success">Create</button>
    </form>
  </div>
</div>

<?php $this->beginBlock('js'); ?>
<script>
Vue.component('v-select', VueSelect.VueSelect);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        message: <?=$message?>,
        m: <?=Json::encode($m, true)?>,
        category: <?=Json::encode($category, true)?>,
        href: {
          index: "<?=Url::to(['spider/index'], true)?>",
        },
      },
      bindCategory: '',
    };
  },
  computed: {
    bindCategoryData: {
      get() {
        return this.bindCategory;
      },
      set(value) {
        this.init.m.fk_category_id = value ? value.id : '';
      }
    }
  },
  methods: {
    setRule(value, filed) {
      let arr = value.split('/');
      var _this = this;
      var tmp = this.init.message;

      arr.forEach(row =>{
        tmp = tmp[row];
      });

      this.init.m[filed] = tmp; 
    }
  }
});
</script>
<?php $this->endBlock(); ?>
