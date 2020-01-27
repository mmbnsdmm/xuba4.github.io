import "./vue-quill-editor";
import { quillEditor } from "vue-quill-editor";
import "./quill-editor.styl"

import { Quill } from 'vue-quill-editor';
// 自定义插入a链接
var Link = Quill.import('formats/link');
class FileBlot extends Link {  // 继承Link Blot
    static create(value) {
        let node = undefined
        if (value&&!value.href){  // 适应原本的Link Blot
            node = super.create(value);
        }
        else{  // 自定义Link Blot
            node = super.create(value.href);
            // node.setAttribute('download', value.innerText);  // 左键点击即下载
            node.innerText = value.innerText;
            node.download = value.innerText;
        }
        return node;
    }
}
FileBlot.blotName = 'link';
FileBlot.tagName = 'A';
Quill.register(FileBlot);

export default quillEditor;