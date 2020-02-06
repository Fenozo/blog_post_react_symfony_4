import React from "react";

class BlogPostContainer extends React.Component {

    componentDidMount() {
        console.log(this.props);
        console.log(this.props.match.params.id)
    }

    render () {
        return (<div>From Post Container</div>);
    };
}

export default BlogPostContainer;