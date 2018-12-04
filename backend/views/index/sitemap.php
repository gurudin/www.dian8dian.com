<?php
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Sitemap';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Sitemap
      <!-- <a href="init.href.save" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> Create
      </a> -->
    </h4>
    <hr>

    <form>
      <div class="form-row">
        <div class="col-md-3 mb-3">
          <label>Get sitemap</label>
          <button type="button" class="btn btn-large btn-block btn-outline-info" @click="getSitemap">
            <i aria-hidden="true" class="fas fa-paper-plane"></i> Get sitemap
          </button>
        </div>

        <div class="col-md-2 ml-auto">
          <label>Generate</label>
          <button type="button" class="btn btn-large btn-block btn-success" @click="generate">
            Generate
          </button>
        </div>
      </div>
    </form>

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Url</th>
          <th scope="col">Title</th>
          <th>Priority</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,inx) in sitemaps">
          <th scope="row">{{inx + 1}}</th>
          <td>
            <a :href="item.priority == 0.8 ? item.url+'/'+item.title : item.url" target="_blank">{{item.url}}</a>
          </td>
          <td>{{item.title}}</td>
          <td>{{item.priority}}</td>
        </tr>
      </tbody>
    </table>
    
    <div class="col-12">
      <div class="float-left"></div>
      <div class="float-right">
        {{sitemaps.length}} Totle
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
        href: {
          sitemap: "<?=Url::to(['index/ajax-sitemap'], true)?>",
          generate: "<?=Url::to(['index/ajax-generate'], true)?>",
        }
      },
      sitemaps: [],
    };
  },
  methods: {
    getSitemap(event) {
      var $btn = $(event.currentTarget).loading('<i aria-hidden="true" class="fas fa-paper-plane"></i> Loading...');
      
      var _this = this;
      $.post(this.init.href.sitemap, {}, function (response) {
        if (response.status) {
          _this.sitemaps = response.data;
        }
        $btn.loading('reset');
      });
    },
    generate(event) {
      var $btn = $(event.currentTarget).loading('Loading...');
      $.post(this.init.href.generate, {}, function (response) {
        console.log(response);
        
        $btn.loading('reset');
      });
    },
  }
});
</script>
<?php $this->endBlock(); ?>
