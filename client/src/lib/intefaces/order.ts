export default interface Order {
    Id: number,
    Reference: String,
    BaseCurrency: String,
    OrderedDate: String,
    ForeignCurrency: String,
    ForeignExchangeRate: number,
    ForeignCurrencyAmount: number,
    BaseCurrencyAmount: number,
    SurchargePercentage: number,
    SurchargeAmount: number,
    FinalAmount: number,
}