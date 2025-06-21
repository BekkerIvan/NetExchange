<script lang="ts">
    import {Button, Card, Heading, Spinner} from "flowbite-svelte";
    import type {Summary} from "$lib/intefaces/summary.ts";

    export let placingOrder: Boolean;
    export let placeOrder;
    export let summary: Summary;
</script>

<Card class="p-4 sm:p-6 md:p-8">
    <Heading tag="h3">Summary:</Heading>
    <div class="flex flex-col gap-y-5 mt-5">
        <span class="border-b-1 border-gray-300 w-full">
            FROM: <span class="float-end">{summary?.From}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            TO: <span class="float-end">{summary?.To}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            {summary?.From.toUpperCase()} PRICE: <span class="float-end">{summary?.FromValue}</span>
        </span>
        <span class="border-b-1 border-gray-300 w-full">
            {summary?.To.toUpperCase()} PRICE: <span class="float-end">{summary?.ToValue}</span>
        </span>
        {#if summary?.Surcharge}
            <Heading tag="h6">Surcharges:</Heading>
            <span class="border-b-1 border-gray-300 w-full">
                PERCENTAGE: <span class="float-end">{summary?.Surcharge.Percentage}%</span>
            </span>
                <span class="border-b-1 border-gray-300 w-full">
                VALUE: <span class="float-end">{summary?.Surcharge.Value}</span>
            </span>
        {/if}
        <Heading tag="h6">Final Payment:</Heading>
        <span class="border-b-1 border-gray-300 w-full">
            ZAR: <span class="float-end">{summary?.PaymentValue}</span>
        </span>
    </div>
    <Button disabled={placingOrder} class="float-right mt-3" onclick={() => placeOrder()}>
        {#if placingOrder}
            <Spinner class="me-3" size="4" color="gray" />Placing Order...
        {:else}
            Place Order
        {/if}
    </Button>
</Card>