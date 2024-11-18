<template>
  <div class="textarea-editor">
    <ckeditor v-model="content" :editor="editor" @ready="onReady" :config="editorConfig"></ckeditor>
    <v-expand-transition>
      <span v-if="errorMessages.length" class="caption error--text">{{errorMessages[0]}}</span>
    </v-expand-transition>
  </div>
</template>

<script>
  import CKEditor from '@ckeditor/ckeditor5-vue'
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
  import es from '@ckeditor/ckeditor5-build-classic/build/translations/es'
  export default {
    name: 'CkeditorTextarea',
    props: {
      value: {
        type: String,
        default: ''
      },
      name: {
        type: String,
        default: null
      },
      errorMessages: Array
    },
    components: {
      ckeditor: CKEditor.component
    },
    inject: ['$validator'],
    $_veeValidate: {
      name () {
        return this.name
      },
      value () {
        // eslint-disable-next-line
        return this.content.replace(/<\s*br[^>]?>/,'\n').replace(/(<([^>]+)>)/g, '').replace('&nbsp;', '')
      }
    },
    data: () => ({
      content: '',
      editorConfig: {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'link', 'insertTable', '|', 'undo', 'redo' ],
        language: 'es'
      },
      editor: ClassicEditor
    }),
    watch: {
      value: {
        immediate: true,
        handler (val) {
          if (typeof val === 'undefined' || val === null) val = ''
          this.content = val
        }
      },
      'content' (val) {
        this.$emit('input', val)
      }
    },
    created () {
      setTimeout(() => {
        this.content = '<div>' + (!this.value ? '' : this.value) + '</div>'
      }, 500)
    },
    methods: {
      onReady (editor) {
        editor.locale = es
      }
    }
  }
</script>

<style lang="scss">
  .textarea-editor .ck-content>p {
    font-size: 1em;
    line-height: 1.3em;
    margin: 0px;
  }
</style>
