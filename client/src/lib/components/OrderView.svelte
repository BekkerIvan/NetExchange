<script lang="ts">
    import {Card, Heading} from "flowbite-svelte";
    import type Order from "$lib/intefaces/order.ts";

    export let order: Order;
</script>

<Card class="p-4 sm:p-6 md:p-8">
    <Heading tag="h3">Order Detail:</Heading>
    <div class="flex flex-col gap-y-5 mt-5">
        <span class="border-b-1 border-gray-300 w-full">
            REFERENCE: <span class="float-end">{order?.Reference}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            DATE: <span class="float-end">{order?.OrderedDate}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            FOREIGN CURRENCY: <span class="float-end">{order?.ForeignCurrency}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            FOREIGN RATE: <span class="float-end">{order?.ForeignExchangeRate}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            FOREIGN AMOUNT: <span class="float-end">{order?.ForeignCurrencyAmount}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            PURCHASED CURRENCY: <span class="float-end">{order?.BaseCurrency}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            PURCHASED AMOUNT: <span class="float-end">{order?.BaseCurrencyAmount}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            SURCHARGE PERCENTAGE: <span class="float-end">{order?.SurchargePercentage}%</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            SURCHARGE AMOUNT: <span class="float-end">{order?.SurchargeAmount}</span>
        </span>
        <Heading tag="h6">Amount Payed:</Heading>
        <span class="border-b-1 border-gray-300 w-full">
            <span class="float-end">{order?.FinalAmount}</span>
        </span>
    </div>
</Card>