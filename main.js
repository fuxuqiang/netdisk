function foo () {}
let bar = new foo
console.log(Object.getPrototypeOf(foo) == Function.prototype) // true
console.log(Object.getPrototypeOf(bar) == foo.prototype) // true

let array = []
console.log(Object.getPrototypeOf(array) == Array.prototype) // true

let object = {}
console.log(Object.getPrototypeOf(object) == Object.prototype) // true

console.log(Object.getPrototypeOf(Function.prototype) == Object.prototype) // true
console.log(Object.getPrototypeOf(Array.prototype) == Object.prototype) // true

// prototype is for types, while Object.getPrototypeOf() is the same for instances.

// 对于复合类型的数据（主要是对象和数组），变量指向的内存地址，保存的只是一个指针