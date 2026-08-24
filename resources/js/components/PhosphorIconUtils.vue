<script setup lang="ts">
import { computed } from 'vue';

interface PhosphorIconDefinition {
    type: string;
    value: string;
    opacity?: number;
    extra?: unknown;
}

interface PhosphorIconData {
    id: number;
    icon_name: string;
    icon_defs: PhosphorIconDefinition[];
}

defineOptions({
    name: 'PhosphorIcon',
    inheritAttrs: false,
});

const props = withDefaults(
    defineProps<{
        item?: PhosphorIconData | null;
        color?: string;
        size?: string | number;
        weight?: string;
        mirrored?: boolean;
    }>(),
    {
        item: null,
        color: 'currentColor',
        size: '1em',
        weight: 'regular',
        mirrored: false,
    },
);

const definition = computed<PhosphorIconDefinition | undefined>(() =>
    props.item?.icon_defs.find(
        (iconDefinition) => iconDefinition.type === props.weight,
    ),
);

const extraPaths = computed<string[]>(() => {
    const extra = definition.value?.extra;

    if (!extra) {
        return [];
    }

    return (Array.isArray(extra) ? extra : [extra]).filter(
        (path): path is string => typeof path === 'string',
    );
});
</script>

<template>
    <svg
        v-if="item && definition"
        v-bind="$attrs"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 256 256"
        :width="size"
        :height="size"
        :fill="color"
        :style="{ transform: mirrored ? 'scaleX(-1)' : undefined }"
    >
        <slot />
        <g>
            <path
                :d="definition.value"
                :opacity="
                    definition.type === 'duotone'
                        ? definition.opacity
                        : undefined
                "
            />
            <path
                v-for="(path, index) in extraPaths"
                :key="index"
                :d="path"
            />
        </g>
    </svg>
</template>
