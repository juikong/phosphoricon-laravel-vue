export interface PhosphorIconDefinition {
    type: string;
    value: string;
    opacity?: number;
    extra?: unknown;
}

export interface PhosphorIcon {
    id: number;
    icon_name: string;
    icon_defs: PhosphorIconDefinition[];
}

export interface Category {
    id: number;
    name: string;
    description: string | null;
    phosphor_icon_id: number | null;
    phosphor_icon?: PhosphorIcon | null;
    sort: number;
    status: number;
}
