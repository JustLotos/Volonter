export default class Logger {

    public static log(value: any, props: object = {}) {
        if(props.label) {
            console.log(props.label);
        }

        if(props.trace) {
            console.trace();
        }

        if (props.encode == 'json') {
            if(value.toJSON) {
                console.log(value.toJSON());
            }
            return;
        }

        console.log(value);
    }
}
