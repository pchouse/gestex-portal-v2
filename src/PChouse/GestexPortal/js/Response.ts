export type Response = {
    html_code?: string;
    js_code?: string;
    error: boolean;
    error_number?: number;
    msg?: string;
    warning?: string;
    error_fields?: string[];
    type_load?: string;
    data?: string;
    extraData?: string;
};
