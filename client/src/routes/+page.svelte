<script lang="ts">
    import CurrencyDropdown from "$lib/components/Currencies.svelte";
    import Conversion from "$lib/components/Conversion.svelte";
    import SummaryView from "$lib/components/SummaryView.svelte";
    import OrderView from "$lib/components/OrderView.svelte";
    import type Currency from "$lib/intefaces/currency.ts";
    import type {Summary} from "$lib/intefaces/summary.ts";
    import type Order from "$lib/intefaces/order.ts";
    import {Alert, Button} from "flowbite-svelte";

    let selectedCurrency: Currency|null = null;
    let calculatedSummary: Summary| null = null;
    let order: Order| null = null;
    let value: number;

    let isValid: Boolean = true;
    let placingOrder: Boolean = false;
    let baseCurrency: Boolean;

    let validationMessage: String = "";
    const isValidCheck = () => {
        isValid = true;
        if (!value) {
            validationMessage = "We require a conversion value to be allocated";
            isValid = false;
            return false;
        }
        if (value < 0) {
            validationMessage = "We require that conversion value greater than 0";
            isValid = false;
            return false;
        }
        return true;
    }

    let placeOrder = async () => {
        order = null;
        if (!isValidCheck()) {
            return;
        }
        placingOrder = true;
        const formData = new FormData();
        formData.set("id", String(selectedCurrency?.Id ?? -1));
        formData.set("value", String(value));
        formData.set("direction", String(baseCurrency));
        const response = await fetch("http://localhost:8080/api/order/place/", {
            method: "POST",
            body: formData
        });
        placingOrder = false;
        calculatedSummary = null;
        if (!response.ok) {
            isValid = false;
            validationMessage = "We are unable to place your order at this time. Please try again later";
            return;
        }
        const conversionData = await response.json();
        calculatedSummary = null;
        order = conversionData.data;
    }

    const summary = async () => {
        calculatedSummary = null;
        order = null;
        if (!isValidCheck()) {
            return;
        }
        const formData = new FormData();
        formData.set("id", String(selectedCurrency?.Id ?? -1));
        formData.set("value", String(value));
        formData.set("direction", String(baseCurrency));
        const response = await fetch("http://localhost:8080/api/currency/convert/", {
            method: "POST",
            body: formData
        });
        if (!response.ok) {
            isValid = false;
            validationMessage = "Could not process your request at this time. Please select another currency or try again later.";
            return;
        }
        const conversionData = await response.json();
        calculatedSummary = conversionData.data;
    }
</script>
<div class="flex gap-x-5">
    <div class="w-1/2 md:w-1/4 flex gap-y-3 flex-col">
        <CurrencyDropdown bind:selected={selectedCurrency}/>
        <Conversion bind:value bind:baseCurrency bind:currency={selectedCurrency}/>
        {#if selectedCurrency}
            <Button class="float-left" onclick={summary}>
                View Summary
            </Button>
        {/if}
        {#if !isValid}
            <Alert color="red" class="mt-3">
                {validationMessage}
            </Alert>
        {/if}
    </div>
    {#if selectedCurrency && calculatedSummary && !order}
        <div class="w-1/2 md:w-1/3 lg:w-1/4">
            <SummaryView bind:placeOrder bind:placingOrder bind:summary={calculatedSummary}/>
        </div>
    {/if}
    {#if order && !calculatedSummary}
        <div class="w-1/2 md:w-1/3 lg:w-1/4">
            <OrderView bind:order/>
        </div>
    {/if}
</div>


