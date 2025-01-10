import {computed} from "vue";

export function useMappings() {
    const taskStatusMap = {
        0: {label: "Pending", color: "dark"},
        1: {label: "In Progress", color: "blue"},
        2: {label: "Completed", color: "green"},
    };

    const getTaskStatusLabel = computed(() => (status) => taskStatusMap[status]?.label || "Unknown");
    const getTaskStatusColor = computed(() => (status) => taskStatusMap[status]?.color || "blue");

    return {
        taskStatusMap,

        getTaskStatusLabel,
        getTaskStatusColor,
    };
}
