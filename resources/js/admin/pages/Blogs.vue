<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div> <p class="_title0">Blogs</p></div>
                        <div>
                            <Button icon="ios-add"
                             @click="$router.push('/createblog')"
                             v-if="isWritePermitted">
                                Create Blog
                            </Button>
                        </div>
                    </div>
                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Categories</th>
                                <th>Tags</th>
                                <th>Views</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->
                            <!-- ITEMS -->
                            <tr v-for="(blog,i) in blogs" :key="i" v-if="blogs.length">
                                <td>{{blog.id}}</td>
                                <td class="_table_name2">{{blog.title}}</td>
                                <td> <span  v-for="(c, j) in blog.category" v-if="blog.category.length"><Tag color="cyan">{{c.categoryName}}</Tag></span> </td>
                                <td> <span  v-for="(t, k) in blog.tag" v-if="blog.tag.length"><Tag color="cyan">{{t.tagName}}</Tag></span> </td>
                                <td>{{ blog.views}}</td>
                                <td>
                                    <Button type="primary" shape="circle" v-if="isReadPermitted" ghost>Visit blog</Button>
                                    <Button type="default" shape="circle" icon="md-trash" @click="showDeleteModal(blog,i)" :loading="blog.isDeleting" v-if="isDeletePermitted"></Button>
                                    <Button type="default" shape="circle" icon="md-create"
                                            @click="$router.push(`/editblog/${blog.id}`)"  v-if="isUpdatePermitted"></Button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!--DELETE TAG MODAL-->
                <delete-modal></delete-modal>
                <!--DELETE TAG MODAL-->
            </div>
        </div>
    </div>
</template>
<script>
import DeleteModal from '../components/DeleteModal.vue';
import {mapGetters} from 'vuex';
export default {
    name:'Blogs',
    components:
        {
            DeleteModal
        },
    computed:
        {
            ...mapGetters(['getDeleteModalObj'])
        },
    watch:
        {
            getDeleteModalObj(obj)
            {
                if (obj.isDeleted)
                {
                    this.blogs.splice(obj.deletingIndex,1);
                    console.log(obj.deletingIndex);
                }
            },
        },
    data(){
        return{
            isAdding:false,
            isDeleting:false,
            deleteModal:false,
            blogs:[],
            index:-1,
            deleteItem:{}
        }
    },
    methods:{

        // async deleteBlog(){
        //     this.isAdding=true
        //     const res = await this.callApi('post' ,'app/delete_blog',this.deleteItem)
        //     if (res.status === 200)
        //     {
        //         this.blogs.splice(this.index,1)
        //         this.s('This blog has been deleted successfully!')
        //         this.isAdding=false
        //         this.deleteModal=false
        //     }
        //     else this.swr()
        //     this.isAdding=false
        // },
        showDeleteModal(blog,i)
        {
            const deleteModalObj =
                {
                    deleteModal :true,
                    dataUrl : 'app/delete_blog',
                    data : {id: blog.id},
                    deletingIndex:i,
                    isDeleted : false,
                    msg : 'Are you sure you want to delete this blog?',
                    successMsg: 'Blog has been deleted successfully!'
                }
            this.$store.commit('setDeleteModalObj',deleteModalObj);
            console.log(deleteModalObj);

        },
    },
    async created(){

        this.getDeleteModalObj.deleteModal=false
        //for extra control
        if(this.isReadPermitted) {
            const res = await this.callApi('get', 'app/get_blogs')
            if (res.status === 200) {
                this.blogs = res.data
            } else {
                this.swr()
            }
        }
    }
}
</script>


