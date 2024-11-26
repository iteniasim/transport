<script setup>
import SelectComponent from '@/Components/SelectComponent.vue';

const props = defineProps({
    user: {
        type: Object,
        default: null,
    },
    roles: {
        type: Array,
        Required: true,
    },
});
</script>

<template>
    <div id="edit-user-form-modal" class="overlay modal overlay-open:opacity-100 hidden" role="dialog" tabindex="-1">
        <div class="modal-dialog overlay-open:opacity-100">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit User</h3>
                    <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3" aria-label="Close"
                        data-overlay="#edit-user-form-modal"><span class="icon-[tabler--x] size-4"></span></button>
                </div>
                <form>
                    <div class="modal-body pt-0">
                        <label class="form-control mb-4">
                            <div class="label">
                                <span class="label-text">Full Name</span>
                            </div>
                            <input name="name" autocomplete="name" type="text" placeholder="John Doe" class="input" />
                        </label>
                        <label class="form-control mb-4">
                            <div class="label">
                                <span class="label-text">Email</span>
                            </div>
                            <input name="email" autocomplete="email" type="email" placeholder="johndoe@123@gmail.com" class="input" />
                        </label>

                        <label class="form-control mb-4">
                            <div class="label">
                                <span class="label-text">Role</span>
                            </div>
                            <SelectComponent :placeholder="'Select a role'" :options="props.roles" :value-key="'id'" :label-key="'name'" />
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft btn-secondary" data-overlay="#edit-user-form-modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
