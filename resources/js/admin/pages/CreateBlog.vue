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
                    <Button @click="save" type="primary">
                    Save Data
                </Button>
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
                    // console.log(this.articleHTML)  it's only show the content of articleHtml in here(then()
                })
                .catch(err => { console.log(err) })

        },
       save(){
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
