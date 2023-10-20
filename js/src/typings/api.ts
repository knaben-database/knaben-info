/**
 * The Response contains content from the fetch library and the PHP API data
 */
interface ApiResponse {
    /**
     * Headers returned by fetch
     */
    headers: Headers,
    /**
     * If the request went ok or not
     */
    ok: boolean,
    /**
     * The return status code
     */
    status: number,
    /**
     * The status text describing the status code
     * For example:
     * ```
     *  OK, Continue, Not Found
     * ```
     */
    statusText: string,
    /**
     * The PHP API may make this true, or false depending on what happened.
     *
     * The error is true when the `Api->Error(...)` is used
     */
    error: boolean,
    /**
     * When an error happens, an error message may be displayed.
     *
     * If none are provided, the returned error message will be the status text.
     *
     * If no text are provided, and no status text are provided, there are a small chance of it being null
     */
    error_msg: string|null,
    /**
     * This is a more in-depth message telling what went wrong.
     * It is usually null, so make sure to check if it is set
     */
    error_desc: string|null,
    /**
     * One may receive multiple requests back at once.
     * Depending on the API used, the developer should know when to check if it is "multi"
     */
    type: "single" | "multi",
    /**
     * The time of the server
     */
    server_time: Date,
    /**
     * The most interesting part.
     *
     * Keep in mind if an error occurs, there won't be any source. Source will be `null`
     */
    source: any
}