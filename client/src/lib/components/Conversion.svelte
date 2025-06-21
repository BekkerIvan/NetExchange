<script lang="ts">
    import { Input, Label, Button, Tooltip} from "flowbite-svelte";
    import {RefreshOutline} from "flowbite-svelte-icons";

    import type Currency from "$lib/intefaces/currency.ts";
    export let currency: null|Currency = null;
    export let baseCurrency: Boolean = true;
    export let value: number = 0;
</script>
{#if currency}
<section class="flex flex-col gap-3">
    <div>
        <Label class="flex justify-between items-center">
            <span>{baseCurrency ? "ZAR" : currency.Code}</span>
            <Button title="Switch between ZAR and {currency?.Code}" color="light" size="xs" onclick={() => baseCurrency = !baseCurrency}><RefreshOutline/></Button>
            <Tooltip placement="top">Switch between ZAR and {currency?.Code}</Tooltip>
        </Label>
        <Input bind:value type="number" min="0"></Input>
    </div>
</section>
{/if}