export type TDashboardResponse = {
    from: string;
    to: string;
    data: [
        {
            value: number;
            legend: string;
        }
    ];
};
