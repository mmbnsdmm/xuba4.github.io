import Auth from '@/common/auth';
import {Toast} from 'vant'
let Article = {
    _getSessionKeyId: function(id) {
        if (!id){
            Toast("未传入文章ID");
            return ;
        }
        return "article_" + id;
    },
    getIsSetById: function (id) {
        let _k = this._getSessionKeyId(id);
        return Auth.getSession(_k);
    },
    getById: function (id, isLast = false) {
        let old = this.getIsSetById(id);
        if (!old) isLast = true;
        this.setById(id);
        return isLast ? this.getIsSetById(id) : old;
    },
    setById: function (id) {
        let article = {};
        Auth.post("/article/default/view", {id: id}, false, function (res) {
            article = res.article;
        }, function (msg) {
            Toast(msg);
        });
        let _k = this._getSessionKeyId(id);
        Auth.setSession(_k, article);
    }
};
export default Article