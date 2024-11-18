<template>
  <div class="document-editor">
    <ckeditor v-model="content" :editor="editor" @ready="onReady" :config="editorConfig"></ckeditor>
  </div>
</template>

<script>
  import CKEditor from '@ckeditor/ckeditor5-vue'
  import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document'
  import es from '@ckeditor/ckeditor5-build-decoupled-document/build/translations/es'
  export default {
    name: 'CkeditorDocument',
    props: {
      value: {
        type: String,
        default: ''
      }
    },
    components: {
      ckeditor: CKEditor.component
    },
    data: () => ({
      content: '',
      editorConfig: {
        language: 'es'
      },
      editor: DecoupledEditor
    }),
    watch: {
      'value' (val) {
        if (typeof val === 'undefined' || val === null) val = ''
        this.content = val
      },
      'content' (val) {
        this.$emit('input', val)
      }
    },
    created () {
      if (typeof this.value === 'undefined' || this.value === null) this.value = ''
      this.content = this.value
    },
    methods: {
      onReady (editor) {
        editor.locale = es
        editor.ui.getEditableElement().parentElement.insertBefore(
          editor.ui.view.toolbar.element,
          editor.ui.getEditableElement()
        )
      }
    }
  }
</script>
<style lang="scss" scoped>
  .document-editor {
    background: antiquewhite !important;
    border: 1px solid var(--ck-color-base-border);
    border-radius: var(--ck-border-radius);

    /* Set vertical boundaries for the document editor. */
    max-height: 29cm;

    /* This element is a flex container for easier rendering. */
    display: flex;
    flex-flow: column nowrap;
  }

  .document-editor>.ck.ck-editor__editable_inline {
    padding: 2cm 2cm 2cm 2cm;
  }

  .document-editor>.ck-editor__editable {
    /* Make it possible to scroll the "page" of the edited content. */
    overflow-y: scroll;
    overflow-x: scroll;
    /* Set the dimensions of the "page". */
    width: 21.7cm;
    min-height: 21cm;
    max-height: 28cm;

    border: 1px hsl( 0,0%,82.7% ) solid;
    border-radius: var(--ck-border-radius);
    background: white;

    /* The "page" should cast a slight shadow (3D illusion). */
    box-shadow: 0 0 5px hsla( 0,0%,0%,.6 );

    /* Center the "page". */
    margin: 10px auto;
  }
  .document-editor .ck-content,
  .document-editor .ck-heading-dropdown .ck-list .ck-button__label {
    font: 16px/1.6 "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  /* Adjust the headings dropdown to host some larger heading styles. */
  .document-editor .ck-heading-dropdown .ck-list .ck-button__label {
    line-height: calc( 1.7 * var(--ck-line-height-base) * var(--ck-font-size-base) );
    min-width: 6em;
  }

  /* Scale down all heading previews because they are way too big to be presented in the UI.
  Preserve the relative scale, though. */
  .document-editor .ck-heading-dropdown .ck-list .ck-button:not(.ck-heading_paragraph) .ck-button__label {
    transform: scale(0.8);
    transform-origin: left;
  }

  /* Set the styles for "Heading 1". */
  .document-editor .ck-content h2,
  .document-editor .ck-heading-dropdown .ck-heading_heading1 .ck-button__label {
    font-size: 2.18em;
    font-weight: normal;
  }

  .document-editor .ck-content h2 {
    line-height: 1.37em;
    padding-top: .342em;
    margin-bottom: .142em;
  }

  /* Set the styles for "Heading 2". */
  .document-editor .ck-content h3,
  .document-editor .ck-heading-dropdown .ck-heading_heading2 .ck-button__label {
    font-size: 1.75em;
    font-weight: normal;
    color: hsl( 203, 100%, 50% );
  }

  .document-editor .ck-heading-dropdown .ck-heading_heading2.ck-on .ck-button__label {
    color: var(--ck-color-list-button-on-text);
  }

  /* Set the styles for "Heading 2". */
  .document-editor .ck-content h3 {
    line-height: 1.86em;
    padding-top: .171em;
    margin-bottom: .357em;
  }

  /* Set the styles for "Heading 3". */
  .document-editor .ck-content h4,
  .document-editor .ck-heading-dropdown .ck-heading_heading3 .ck-button__label {
    font-size: 1.31em;
    font-weight: bold;
  }

  .document-editor .ck-content h4 {
    line-height: 1.24em;
    padding-top: .286em;
    margin-bottom: .952em;
  }

  /* Make the block quoted text serif with some additional spacing. */
  .document-editor .ck-content blockquote {
    font-family: Georgia, serif;
    margin-left: calc( 2 * var(--ck-spacing-large) );
    margin-right: calc( 2 * var(--ck-spacing-large) );
  }
</style>

<style lang="scss">
  .document-editor>.ck-content>figure.table {
    width: 100% !important;
  }
  .document-editor>.ck-content>figure.table>table {
    width: 100% !important;
    /*border-color: transparent;*/
  }
  .document-editor>.ck-content>figure.table>table td {
    padding: 0px !important;
    /*border-color: transparent;*/
  }
  .document-editor>.ck-content>p {
    font-size: 1em;
    line-height: 1.1em;
    margin: 0px;
  }
  .document-editor>.ck-toolbar {
    /*position: fixed;*/
    /* Make sure the toolbar container is always above the editable. */
    z-index: 1;

    /* Create the illusion of the toolbar floating over the editable. */
    box-shadow: 0 0 5px hsla( 0,0%,0%,.6 );

    /* Use the CKEditor CSS variables to keep the UI consistent. */
    border-bottom: 1px solid var(--ck-color-toolbar-border);
    border: 1px;
    border-radius: 0;
  }
</style>
