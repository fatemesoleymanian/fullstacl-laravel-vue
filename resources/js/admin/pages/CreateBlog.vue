<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div> <p class="_title0">Create bloge</p></div>
                    </div>
                        <div class="_input_field">
                            <Input type="text" v-model="data.title" placeholder="Title" />
                        </div>
                    <div class="_overflow _table_div blog_editor">
                        <editor
                            ref="editor"
                            autofocus
                            holder-id="codex-editor"
                            save-button-id="save-button"
                            :init-data="initData"
                            @save="onSave"
                            :config="{tools: {
                                      header: require('@editorjs/header'),
                                      list:require('@editorjs/list'),
                                      paragraph:require('@editorjs/paragraph'),
                                      table:require('@editorjs/table'),
                                      image:require('@editorjs/image'),
                                      personality:require('@editorjs/personality'),
                                      },
                                }"
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
                        <Button type="info" v-if="isWritePermitted" @click="save" :loading="isCreating" :disabled="isCreating">{{isCreating ? 'Please wait...' : 'Create blog'}}</Button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:'CreateBlog',
    data() {
        return {
            initData: null,
            data: {
                title : '',
                post : '',
                post_excerpt : '',
                metaDescription : '',
                category_id : [],
                tag_id : [],
                jsonData: null
            },
            articleHTML: '',
            category : [],
            tag : [],
            isCreating: false,
        }
    },
    methods:{

        async onSave(){
            this.$refs.editor._data.state.editor.save()
                .then(async (data) => {
                    await this.outputHtml(data.blocks);
                    //  it's only show the content of articleHtml in here(then()
                    this.data.post = this.articleHTML
                    this.data.jsonData = JSON.stringify(data);
                    if(this.data.post.trim()=='') return this.error(true,'Post')
                    if(this.data.title.trim()=='') return this.error(true,'Title')
                    if(this.data.post_excerpt.trim()=='') return this.error(true,'Post excerpt')
                    if(this.data.metaDescription.trim()=='') return this.error(true,'Meta description')
                    if(!this.data.tag_id.length) return this.error(true,'Tag')
                    if(!this.data.category_id.length) return this.error(true,'Category')
                    const res = await this.callApi('post', 'app/create-blog', this.data)
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
                })
                .catch(err => { console.log(err) })

            this.isCreating = false
        },
       save(){
           console.log(this.initData)
          this.onSave();
        },
        outputHtml(articleObj){
            articleObj.map(obj => {
                switch (obj.type) {
                    case 'paragraph':
                        this.articleHTML += this.makeParagraph(obj);
                        break;
                    case 'image':
                        this.articleHTML += this.makeImage(obj);
                        break;
                    case 'header':
                        this.articleHTML += this.makeHeader(obj);
                        break;
                    case 'raw':
                        this.articleHTML += `<div class="ce-block">
					<div class="ce-block__content">
					<div class="ce-code">
						<code>${obj.data.html}</code>
					</div>
					</div>
				</div>\n`;
                        break;
                    case 'code':
                        this.articleHTML += this.makeCode(obj);
                        break;
                    case 'list':
                        this.articleHTML += this.makeList(obj)
                        break;
                    case "quote":
                        this.articleHTML += this.makeQuote(obj)
                        break;
                    case "warning":
                        this.articleHTML += this.makeWarning(obj)
                        break;
                    case "checklist":
                        this.articleHTML += this.makeChecklist(obj)
                        break;
                    case "embed":
                        this.articleHTML += this.makeEmbed(obj)
                        break;
                    case 'delimeter':
                        this.articleHTML += this.makeDelimeter(obj);
                        break;
                    default:
                        return '';
                }
            });
        },
    },
    async created()
    {
        const [cat,tag] = await  Promise.all(
            [
                this.callApi('get', 'app/get_categories'),
                this.callApi('get', 'app/get_tags'),
            ]
        );
        if(cat.status==200){
            this.category = cat.data
            this.tag = tag.data
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
