export interface Summary {
    From: string,
    To: string,
    FromValue: number,
    ToValue: number,
    PaymentValue: number,
    Surcharge?: Surcharge,
}

export interface Surcharge {
    Value: number
    Percentage: number
}