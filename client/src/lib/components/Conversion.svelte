<script lang="ts">
    import { Input, Label} from "flowbite-svelte";
    import type Currency from "$lib/intefaces/currency.ts";
    export let currency: null|Currency = null;

    export let currencyValue: number;
    export let zarValue: number;
</script>
{#if currency}
<section class="flex flex-col gap-3">
    <div>
        <Label>ZAR</Label>
        <Input onchange={() => currencyValue = 0} bind:value={zarValue} type="number" min="0"></Input>
    </div>
    <span class="text-gray-500 w-full text-center">- OR -</span>
    <div>
        <Label>{currency.Code}</Label>
        <Input onchange={() => zarValue = 0} bind:value={currencyValue} type="number" min="0"></Input>
    </div>
</section>
{/if}