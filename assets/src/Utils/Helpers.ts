import Vue from "vue";

export function setDeckStoreState(state, deck)
{
    let newDeck = cloneObject(deck);
    if (newDeck.cards) {
        newDeck.cards = newDeck.cards.map(card => card.id);
    }
    Vue.set(state.byId, newDeck.id, newDeck);
    if (!state.allIds.includes(newDeck.id)) {
        state.allIds.push(newDeck.id);
    }

}

export function setCardStoreState(state, card, id = null)
{
    let frontRecords = [];
    let backRecords = [];

    let newCard = cloneObject(card);

    if (id) {
        newCard.deck = id;
    }

    if (card.records) {
        card.records.forEach((record)=>{
            if (record.side === 0) {
                frontRecords.push(record);
            } else {
                backRecords.push(record);
            }
        });

        Vue.delete(newCard, 'records');
        newCard.frontRecords = frontRecords;
        newCard.backRecords = backRecords;
    }

    Vue.set(state.byId, newCard.id, newCard);
    if (!state.allIds.includes(newCard.id)) {
        state.allIds.push(newCard.id);
    }
}

export function cloneObject(object) {
    if(object instanceof Object) {
        return JSON.parse(JSON.stringify(object));
    } else {
        return object;
    }
}

export function validate({ params })
{
    return /^\d+$/.test(params.id);
}

export function deckDefault(
    limit_repeat = '',
    limit_learning = '',
    difficulty_index = '',
    base_index = '',
    modifier_index = '',
    name = '',
    description = '',
) {
    return {
        name: name,
        description: description,
        limit_repeat: limit_repeat,
        limit_learning: limit_learning,
        difficulty_index: difficulty_index,
        base_index: base_index,
        modifier_index: modifier_index
    }
}

export function cardDefault(
    name = '',
    frontRecords = [{content:''}],
    backRecords = [{content:''}],
) {
    return {
        name: name,
        frontRecords: frontRecords,
        backRecords: backRecords,
    }
}

export function phpDateIntervalToSeconds(value: string) {

}