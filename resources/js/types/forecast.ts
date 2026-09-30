export interface ForecastDay {
    date: string;
    cards: number;
}

export interface ReviewForecast {
    days: ForecastDay[];
    newWaiting: number;
}
