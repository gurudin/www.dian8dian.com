<?php
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Article List';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Article
    <a :href="init.href.save" class="btn btn-success float-right">Create</a>
    </h4>
    <hr>

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
          <td>{{item.status}}</td>
          <td>{{date.format('Y/M/D H:m', item.created_at)}}</td>
          <td>
            <button
              type="button"
              class="btn btn-warning btn-sm text-white"
              @click="toEdit(item.id)">
              <i class="fas fa-edit"></i></button>
            
            <button
              type="button"
              class="btn btn-danger btn-sm">
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
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        list: <?=Json::encode($result['list'], true)?>,
        category: <?=Json::encode($category, true)?>,
        href: {
          save: "<?=Yii::$app->request->hostInfo . Url::to(['/article/save'])?>",
        },
      },
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
      var url = new URL(this.init.href.save);
      url.searchParams.append('id', id);
      
      window.location = url;
    },
  }
});
</script>
<?php $this->endBlock(); ?>
