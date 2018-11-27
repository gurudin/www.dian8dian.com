<?php
use yii\helpers\Json;
use yii\helpers\Url;

\backend\assets\AppAsset::addScript($this, ['vue-upload-picker.js']);

$this->title = 'Spider';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">
      Set spider <small class="text-muted">create & update</small>
      <a :href="init.href.index" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <form>
      <div class="col-12">
        <div class="form-group">
          <label>Rule name</label>
          <input type="text" class="form-control" v-model.trim="init.title" placeholder="Rule name">
        </div>

        <div class="form-group">
          <label>Url</label>
          <textarea
            class="form-control"
            :class="isTextarea ? 'is-valid' : 'is-invalid'"
            rows="5"
            v-model="textareaData"
            @keyup="changeTextarea"
            :placeholder="placeholder"></textarea>
        </div>

        <table class="table table-borderless table-secondary">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">URL</th>
              <th scope="col">Mehtod</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item,inx) in init.target">
              <th scope="row">{{inx+1}}</th>
              <td>{{item.url}}</td>
              <td>{{item.method}}</td>
            </tr>
          </tbody>
        </table>

        <div class="form-group">
          <label>Mode</label>
          <select class="form-control" v-model="init.m.mode">
            <option value="web">Web</option>
            <option value="api">Api</option>
          </select>
        </div>

        <div class="card">
          <div class="card-body text-info">
            <h6 class="card-title">模式 {{init.m.mode.toUpperCase()}}</h6>
            <p v-if="init.m.mode == 'web'">Web 模式输入正则表达式.</p>
            <p v-if="init.m.mode == 'api'">Api 模式输入获取字段深度. 例: data/list</p>
          </div>
        </div>

        <br>
        <div class="input-group mb-3" style="width: 300px;">
          <div class="input-group-prepend">
            <span class="input-group-text">Add rule:</span>
          </div>
          <input type="text" class="form-control" placeholder="Enter take name" ref="take-name">
          <div class="input-group-append">
            <button class="btn btn-info" type="button" @click="addTakeName">add</button>
          </div>
        </div>
        <hr>

        <!-- Add rule -->
        <div class="form-group" v-for="(item,key) in init.m" v-if="key!='mode'">
          <label>{{key.substring(0,1).toUpperCase() + key.substring(1)}}</label>

          <!-- Image url base -->
          <div class="input-group mb-3" v-if="init.m[key].type == 'image'">
            <div class="input-group-prepend">
              <span class="input-group-text">Image url base:</span>
            </div>
            <input type="text" class="form-control" v-model.trim="init.m[key].base" placeholder="example: http://img.com/">
          </div>
          <!-- /Image url base -->

          <div class="input-group">
            <input type="text" class="form-control" v-model.trim="init.m[key].value" :placeholder="'Enter ' + key + ' rule'">
            <!-- Mode web -->
            <div class="input-group-append" v-if="init.m.mode == 'web'">
              <button class="btn btn-outline-secondary" type="button">Test</button>
            </div>

            <!-- Mode api -->
            <div class="input-group-append" v-if="init.m.mode == 'api'">
              <select class="custom-select" v-model="init.m[key].type">
                <option value="string">String</option>
                <option value="array">Array</option>
              </select>
            </div>
          </div>
          <small class="form-text text-muted">{{result[key]}}</small>
        </div>
        <!-- /Add rule -->

      </div>

      <div class="form-group col-4">
        <button type="button" class="btn btn-outline-info" @click="getForData">
          <i class="fas fa-paper-plane"></i> Get for data
        </button>
      </div>

      <div class="form-group col-4">
        <button type="button" class="btn btn-success" @click="save">
          Save rule
        </button>
      </div>
    </form>
  </div>
</div>

<?php $this->beginBlock('js'); ?>
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      result: '',
      init: {
        title: '',
        m: {
          mode: 'api',
          // title: {value: '', type: 'string'},
          // tags: {value: '', type: 'array'},
        },
        target: [
          // {url: 'http://www.wheelsfactory.cn/api/getTagByPluginItemId?id=56', method: 'get'},
          // {url: 'http://www.wheelsfactory.cn/api/getPluginById?id=56', method: 'post'}
        ],
        href: {
          index: "<?=Url::to(['spider/index'], true)?>",
          getData: "<?=Url::to(['spider/ajax-get-data'], true)?>",
          save: "<?=Url::to(['spider/ajax-save'], true)?>",
        },
      },
      textareaData: '',
      isTextarea: false,
    };
  },
  computed: {
    placeholder() {
      return 'Example:\r\nhttp://example.com get \r\nhttp://example.com post';
    },
  },
  methods: {
    changeTextarea() {
      if (this.textareaData == '') {
        this.isTextarea = false;
        return false;
      }

      var tmpArr = [];
      var target = [];
      var validate = 1;
      this.textareaData.split('\n').forEach(row => {
        if (row) {
          tmpArr.push($.trim(row));
        }
      });
      
      
      tmpArr.forEach(row =>{
        var i = 0;
        var tmp = {url: '', method: ''};
        
        row.split(/\t| |,|  /g).forEach(e => {
          if (i == 0) {
            tmp['url'] = e;
          }
          if (i == 1) {
            tmp['method'] = e;
          }
          i++;
        });
        
        if (tmp['url'] == '' || tmp['method'] == '') {
          validate = 0;
        }
        target.push(tmp);
      });

      this.isTextarea = validate;
      this.init.target = target;
    },
    addTakeName() {
      let name = this.$refs['take-name'].value;
      if (name == '') {
        return false;
      }

      this.$refs['take-name'].value = '';
      this.$set(this.init.m, name, {value: '', type: 'string'});
    },
    getForData(event) {
      var $btn = $(event.currentTarget).loading('loading...');
      var _this = this;
      $.post(this.init.href.getData, {
        target: this.init.target,
        data: this.init.m,
      }, function (response) {
        if (response.status) {
          _this.result = response.data;
        }
        $btn.loading('reset');
      });
    },
    save(event) {
      $.post(this.init.href.save, {
        id: '',
        title: this.init.title,
        m: this.init.m,
        target: this.init.target,
        data: this.result,
      }, function (response) {
        console.log(response);
        if (response.status) {
          window.location.href = this.init.href.index;
        } else {
          $.alert({message: response.msg});
        }
      });
    },
  }
});
</script>
<?php $this->endBlock(); ?>
