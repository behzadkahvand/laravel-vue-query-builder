export interface PostInterface {
    id: string,
    title: string,
    content: string,
    views: number,
    timestamp?: number
}

export interface PostListInterface {
    currentPage?: number,
    data: PostInterface[],
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    next_page_url?: string,
    path: string,
    per_page: number,
    prev_page_url?: string,
    to: number,
    total: number
}

export interface postSavePayload {
    post: PostInterface,
    isUpdate: boolean
}

interface QueryBuilderKey {
    name: string,
    id: string
}

interface RelationalOperator {
    name: string,
    id: string
}

interface LogicalOperator {
    name: string,
    id: string,
    operandCount: number
}

export interface QueryBuilderOptions {
    keys: QueryBuilderKey[],
    relationalOperators: RelationalOperator[]
    logicalOperators: LogicalOperator[],
}

export interface RuleQuery {
    key: string,
    operator: string,
    value: string
}

export interface GroupQuery {
    condition: string,
    rules: (RuleQuery | GroupQuery)[]
}
