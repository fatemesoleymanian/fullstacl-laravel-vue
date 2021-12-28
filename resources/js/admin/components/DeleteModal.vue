<template>
    <div>
        <!--DELETE CATEGORY MODAL-->
        <Modal :value="this.getDeleteModalObj.deleteModal"
               width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
                <p>{{this.getDeleteModalObj.msg}}</p>

            </div>
            <div slot="footer">
                <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteCategory">Delete</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
export default {
    name: "DeleteModal",
    data(){
        return{
            isDeleting:false
        }
    },
    methods:
        {
            async deleteCategory(){
                this.isDeleting=true
                const res = await this.callApi('post' ,this.getDeleteModalObj.dataUrl,this.getDeleteModalObj.data)
                if (res.status === 200)
                {
                    this.$store.commit('setDeleteModal',true);
                    this.s(this.getDeleteModalObj.successMsg);
                    // console.log(this.getDeleteModalObj.successMsg)
                    // this.isDeleting=false
                    // this.deleteModal=false
                }
                else {
                    this.swr()
                    this.$store.commit('setDeleteModal',false);
                }
                this.isDeleting=false

            },
        },
    computed:
        {
            ...mapGetters(['getDeleteModalObj'])
        }

}
</script>

