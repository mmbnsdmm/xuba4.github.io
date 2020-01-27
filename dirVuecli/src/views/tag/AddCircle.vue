<template>
    <div class="tag-add-circle">
        <van-panel title="创建圈子" desc="名称不能超过12个字符" status="">
            <van-cell-group>
                <van-field v-model="tagName" label="名称" v-verify="tagName" :error-message="tagNameErrMsg" required placeholder="请输入圈子名称"/>
            </van-cell-group>
            <div slot="footer">
                <van-button size="large" type="info" @click="add">创建</van-button>
            </div>
        </van-panel>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    export default {
        name: "AddCircle",
        data: function () {
            return {
                tagName: "",
                tagNameErrMsg: ""
            }
        },
        verify: {
            tagName: ["required", {minLength: 1}, {maxLength: 12}],
        },
        methods: {
            add: function () {
                this.tagNameErrMsg = "";
                if (!this.$verify.check()) {
                    if (this.$verify.$errors.tagName) {
                        this.tagNameErrMsg = this.$verify.$errors.tagName[0];
                    }
                    return ;
                }
                let data = this.$auth.authPost("/tag/circle/add", {
                    name: this.tagName
                });
                if (data.is_ok === 1){
                    Toast(data.msg);
                    this.$tool.delCache("circle_list");
                    this.$router.push("/tag/my-circle");
                }else{
                    Toast(data.msg);
                }
            }
        }
    }
</script>

<style scoped>

</style>