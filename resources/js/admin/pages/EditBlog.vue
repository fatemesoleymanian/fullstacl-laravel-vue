<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div> <p class="_title0">Update bloge</p></div>
                    </div>
                    <div class="_input_field">
                        <Input type="text" v-model="data.title" placeholder="Title" />
                    </div>
                    <div class="_overflow _table_div blog_editor">
                        <tinymce
                            id="blog"
                            apiKey="6xgh3kbhilxszad3hk94xpffmdy2d8lt4b0ctptxk3ant76l"
                            initialValue="<p>Initial editor content</p>"
                            :init="{
                          height: 1000,
                          menubar: true,
                          plugins: [
                            'advlist autolink lists link image charmap',
                            'searchreplace visualblocks code fullscreen',
                            'print preview anchor insertdatetime media',
                            'paste code help wordcount table'
                          ],
                          toolbar:
                            'undo redo | formatselect | bold italic | \
                            alignleft aligncenter alignright | \
                            bullist numlist outdent indent | help'
                        }"
                            v-model="data.post"
                        />
                    </div>
                    <div class="_input_field">
                        <Input  type="textarea" v-model="data.post_excerpt" :rows="4" placeholder="Post excerpt " />
                    </div>
                    <div class="_input_field">
                        <Select  filterable multiple placeholder="Select category" v-model="data.category_id">
                            <Option v-for="(c, i) in category" :value="c.id" :key="i">{{ c.categoryName }}</Option>
                        </Select>
                    </div>
                    <div class="_input_field">
                        <Select  filterable multiple placeholder="Select tag" v-model="data.tag_id">
                            <Option v-for="(t, i) in tag" :value="t.id" :key="i">{{ t.tagName }}</Option>
                        </Select>
                    </div>
                    <div class="_input_field">
                        <Input  type="textarea" v-model="data.metaDescription" :rows="4" placeholder="Meta description" />
                    </div>
                    <div class="_input_field">
                        <Button type="info" @click="save" :loading="isCreating" :disabled="isCreating">{{isCreating ? 'Please wait...' : 'Update blog'}}</Button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:'EditBlog',
    data() {
        return {
            data: {
                title : '',
                post : '',
                post_excerpt : '',
                metaDescription : '',
                category_id : [],
                tag_id : []
            },
            articleHTML: '',
            category : [],
            tag : [],
            isCreating: false,
        }
    },
    methods:{

        async save(){
            this.isCreating = true
            this.data.post=tinymce.get("blog").getContent();
            if(this.data.post.trim()=='') return this.error(true,'Post')
            if(this.data.title.trim()=='') return this.error(true,'Title')
            if(this.data.post_excerpt.trim()=='') return this.error(true,'Post excerpt')
            if(this.data.metaDescription.trim()=='') return this.error(true,'Meta description')
            if(!this.data.tag_id.length) return this.error(true,'Tag')
            if(!this.data.category_id.length) return this.error(true,'Category')
            const res = await this.callApi('post',  `/app/update_blog/${this.$route.params.id}`, this.data)
            if(res.status===200){
                this.s('Blog has been created successfully!')
                // redirect...
                this.$router.push('/blogs')
            }
            else{
                if(res.status==422){
                    for(let i in res.data.errors){
                        this.error(true,res.data.errors[i][0])
                    }

                }
                if(res.status==401){
                    this.error(true,res.data.errors)
                }
                else{
                    this.swr()
                }
            }
            this.isCreating = false
        }
    },
    async created()
    {
        const id = parseInt(this.$route.params.id)
        if(!id){
            return this.$router.push('/notfound')
        }
        const [blog, cat, tag] = await Promise.all([
            this.callApi('get', `/app/blog_single/${id}`),
            this.callApi('get', '/app/get_categories'),
            this.callApi('get', '/app/get_tags'),
        ])
        if(blog.status==200){
            if(!blog.data) return this.$router.push('/notfound')

            // console.log(blog)
            this.category = cat.data
            this.tag = tag.data
            tinymce.get("blog").setContent(blog.data.post);
            for(let t of blog.data.tag){
                this.data.tag_id.push(t.id)
            }
            for(let c of blog.data.category){
                this.data.category_id.push(c.id)
            }
            this.data.title = blog.data.title
            this.data.metaDescription = blog.data.metaDescription
            this.data.post_excerpt = blog.data.post_excerpt


        }else{
            this.swr()
        }
    }
}
</script>
<style scoped>
.blog_editor {
    width: 717px;
    margin:30px 0 ;
    padding: 4px 7px;
    font-size: 14px;
    border: 1px solid #dcdee2;
    border-radius: 4px;
    color: #515a6e;
    background-color: #fff;
    background-image: none;
    z-index:  -1;
}
.blog_editor:hover {
    border: 1px solid #57a3f3;
}
._input_field{
    margin: 20px 0 0 0;
    width: 717px;
}
</style>
