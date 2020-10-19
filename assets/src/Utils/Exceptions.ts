export function OperationForbidden(message: string = 'Операция запрщена')
{
    this.message = message;
    this.name = "OperationForbidden";
}