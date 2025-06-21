<?php
require_once "currency.base.php";
class GBP extends CurrencyBase {
    const string CODE = 'GBP';

    public static function getSurchargePercentage(): float {
        return 0.05;
    }

    public static function doAfterOrderPlaced(Order $OrderObj): bool {
        $IsValidBool = parent::doAfterOrderPlaced($OrderObj);
        $EmailerObj = new Emailer();
        return $IsValidBool && $EmailerObj->send("bekkerivan665@gmail.com", $OrderObj->Reference, <<<HTML
            <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                <h2 style="color: #4CAF50;">Order Confirmation</h2>
                <p>An order has been placed with the following details:</p>
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="border: 1px solid #ddd; padding: 8px;">DATE</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">REFERENCE</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">FOREIGN CURRENCY</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">FOREIGN RATE</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">FOREIGN AMOUNT</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">PURCHASED CURRENCY</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">PURCHASED AMOUNT</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">SURCHARGE PERCENTAGE</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">SURCHARGE AMOUNT</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">AMOUNT PAYED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->OrderedDate->format(DATE_TIME_FORMAT)}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->Reference}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->ForeignCurrency}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->ForeignExchangeRate}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->ForeignCurrencyAmount}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->BaseCurrency}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->BaseCurrencyAmount}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->SurchargePercentage}%</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->SurchargeAmount}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{$OrderObj->FinalAmount}</td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-top: 20px;">Thank you for your order!</p>
            </div>
        HTML);
    }
}