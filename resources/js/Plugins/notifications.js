import { router, usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'

const { success, error } = useToast()

export const notifications = {
    install() {
        router.on('finish', () => {
            const { props } = usePage()

            if (props.flash?.success) {
                success(props.flash?.success)
            }

            if (props.flash?.error) {
                error(props.flash?.error)
            }
        })
    },
}
