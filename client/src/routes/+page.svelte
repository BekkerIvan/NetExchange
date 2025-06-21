<script lang="ts">
    import CurrencyDropdown from "$lib/components/Currencies.svelte";
    import Conversion from "$lib/components/Conversion.svelte";
    import type Currency from "$lib/intefaces/currency.ts";
    import {Alert, Button} from "flowbite-svelte";

    let selectedCurrency: Currency|null = null;
    let zarValue: number = 0;
    let currencyValue: number = 0;

    let isValid: Boolean = true;
    let validationMessage: String = "";
    const placeOrder = async () => {
        isValid = true;
        if (!zarValue && !currencyValue) {
            validationMessage = "We require that at least one of the conversion value to be allocated";
            isValid = false;
            return;
        }
        if (zarValue < 0 || currencyValue < 0) {
            validationMessage = "We require that none of the values be less than 0";
            isValid = false;
            return;
        }
    }

    const convert = async () => {
        isValid = true;
        const formData = new FormData();
        formData.set("id", String(selectedCurrency?.Id ?? -1));
        formData.set("from", String(zarValue));
        formData.set("to", String(currencyValue));
        const response = await fetch("http://localhost:5002/api/currency/convert/", {
            method: "POST",
            body: formData
        });
        if (!response.ok) {
            isValid = false;
            validationMessage = "Could not process your request at this time. Please select another currency or try again later.";
            return;
        }
        const conversionData = await response.json();
        zarValue = parseFloat(conversionData.data.from);
        currencyValue = parseFloat(conversionData.data.to);
    }
</script>
<div class="flex">
    <div class="w-1/2 flex gap-y-3 flex-col">
        <div class="flex justify-between items-end-safe">
            <CurrencyDropdown bind:selected={selectedCurrency}/>
            <Button onclick={convert}>Convert</Button>
        </div>
        <Conversion bind:zarValue bind:currencyValue bind:currency={selectedCurrency}/>
    </div>
    <div class="w-1/2">
        {#if selectedCurrency}
            <Button onclick={placeOrder}>BUY</Button>
        {/if}
    </div>
</div>

{#if !isValid}
    <Alert color="red" class="mt-3">
        {validationMessage}
    </Alert>
{/if}

