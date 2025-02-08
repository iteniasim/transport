import {router, usePage} from '@inertiajs/vue3'
import {useToast} from 'vue-toastification'

const {success, info, error} = useToast()

export const notification = {
    install() {
        router.on('finish', () => {
            const { props } = usePage()

            if (props.flash?.success) {
                success(props.flash?.success)
            }

            if (props.flash?.info) {
                info(props.flash?.info)
            }

            if (props.flash?.error) {
                error(props.flash?.error)
            }
        })
    },
}

export const notifications = {
    install() {
        router.on('finish', () => {
            const {flash} = usePage().props

            // only one type of message can be flashed at a time, this extracts the message
            const message = flash?.success || flash?.info || flash?.error

            if (message) {
                switch (message) {
                    case flash?.success:
                        success(flash.success)
                        break
                    case flash?.info:
                        info(flash.info)
                        break
                    case flash?.error:
                        error(flash.error)
                        break
                }
            }
        })
    },
}
