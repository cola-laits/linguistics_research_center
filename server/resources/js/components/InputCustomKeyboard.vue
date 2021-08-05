<template>
    <div>
    <input class="form-control"
           :name="name"
           :type="type"
           :value="value"
           @input="input"
           autocomplete="off"
           :placeholder="placeholder"
           ref="input"
           @keydown="check_mark_shifting($event, true)"
           @keyup="check_mark_shifting($event, false)"
           @focus="on_input_focus()"
           @blur="on_input_blur()"
    >
        <transition name="keyboard-fade">

            <div ref="toolbar" class="custom_keyboard_toolbar"
                 v-if="should_show_custom_keyboard()"
                 @mousedown.prevent
            >
                <div class="custom_keyboard_button" v-for="c in custom_keyboard"
                    @mousedown.prevent="select_character(c)"
                >{{get_display_char(c)}}</div>
            </div>
        </transition>

    </div>
</template>

<script>
    export default {
        props: [
            'name',
            'value',
            'type',
            'placeholder',
            'custom_keyboard'
        ],
        data: function() { return {
            focused: false,
            shifting: false,
        }},
        methods: {
            input(event) {
                this.$emit('input', event.target.value);
            },
            get_display_char(c) {
                if (this.shifting) {
                    return c.toUpperCase();
                }
                return c;
            },
            check_mark_shifting(event, s) {
                const KEYCODE_SHIFT = 16;
                if (event.which === KEYCODE_SHIFT) {
                    this.shifting = s;
                }
            },
            mark_hovering(h) {
                this.hovering = h;
            },
            select_character(c) {
                let value = this.get_display_char(c);

                let startPos = this.$refs.input.selectionStart;
                let endPos = this.$refs.input.selectionEnd;
                this.$refs.input.value = this.$refs.input.value.substring(0, startPos) + value +
                            this.$refs.input.value.substring(endPos, this.$refs.input.value.length);
                this.$refs.input.selectionStart = startPos + value.length;
                this.$refs.input.selectionEnd = startPos + value.length;

                this.$emit('input', this.$refs.input.value);
            },
            on_input_focus() {
                this.focused = true;
            },
            on_input_blur() {
                this.focused = false;
            },
            should_show_custom_keyboard() {
                return this.custom_keyboard.length>0
                    && this.focused;
            }
        },
        updated() {
            if (this.$refs.toolbar) {
                this.$refs.toolbar.style.right = '0px';
                this.$refs.toolbar.style.top = (this.$refs.input.offsetHeight + 32) + 'px';
            }
        }
    }
</script>

<style scoped>
    .custom_keyboard_toolbar {
        position:absolute;
        z-index:100;
        border: 1px solid #cccccc;
        background: #f0f0ee;
        padding: 3px;
    }

    .custom_keyboard_button {
        display:inline-block;
        width: 20px;
        height: 23px;
        color: #000000;
        text-align: center;
        cursor: pointer;
        border: 1px solid #f0f0ee;
        padding-top: 2px;
        margin:2px;
    }

    .custom_keyboard_button:hover {
        border: 1px solid #000000;
        background: #b2bbd0;
    }

    .keyboard-fade-enter,
    .keyboard-fade-leave-to { opacity: 0 }

    .keyboard-fade-leave,
    .keyboard-fade-enter-to { opacity: 1 }

    .keyboard-fade-enter-active,
    .keyboard-fade-leave-active { transition: opacity 200ms }
</style>
