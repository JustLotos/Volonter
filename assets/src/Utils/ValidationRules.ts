export const Rules = {
    required: v => !!v || 'Required.',
    minL8: v => v.length >= 8 || 'Min 8 characters',
    email: value => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        return pattern.test(value) || 'Invalid e-mail.'
    },
}

export const PasswordRules = [Rules.required, Rules.minL8];
export const EmailRules = [Rules.required, Rules.email];
export const PlainPasswordRules = [Rules.required];