<script lang="ts">
    import {onMount} from "svelte";
    import type Currency from "$lib/intefaces/currency.ts";
    import {Button, Dropdown, DropdownGroup, DropdownItem, Label} from "flowbite-svelte";
    import {ChevronDownOutline} from "flowbite-svelte-icons";

    export let loading: Boolean = false
    export let selected: Currency|null = null
    export let currencies: Array<Currency> = [];
    onMount(async () => {
        loading = true;
        const response = await fetch("http://localhost:8080/api/currency/loadAll/");
        loading = false;
        if (!response.ok) {
            currencies = [];
        }
        currencies = (await response.json()).data;
    });
</script>
<section>
    <Label>Please select a currency</Label>
    <Button class="w-48">
        {#if loading}
            loading...
        {:else}
            {#if selected}
                {selected.Code}<ChevronDownOutline/>
            {:else}
                Currencies <ChevronDownOutline/>
            {/if}
        {/if}
    </Button>
    <Dropdown class="h-48 w-48 overflow-y-auto py-1">
        <DropdownGroup>
            <DropdownItem class="w-full" onclick={() => selected = null}>
                Reset
            </DropdownItem>
            {#each currencies as currency}
                <DropdownItem class="w-full flex justify-between" onclick={() => selected = currency}>
                    <span>{currency.Code}</span>
                    <span>{currency.ExchangeRate}</span>
                </DropdownItem>
            {/each}
        </DropdownGroup>
    </Dropdown>
</section>